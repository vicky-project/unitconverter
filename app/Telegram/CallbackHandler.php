<?php

namespace Modules\UnitConverter\Telegram;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\UnitConverter\Services\UnitDiscovery;
use Modules\UnitConverter\Services\UnitConverterService;
use Modules\Telegram\Services\Support\InlineKeyboardBuilder;
use Modules\Telegram\Services\Support\TelegramApi;
use Modules\Telegram\Services\Handlers\Callbacks\BaseCallbackHandler;

class CallbackHandler extends BaseCallbackHandler
{
  protected UnitDiscovery $unitDiscovery;
  protected UnitConverterService $converterService;
  protected InlineKeyboardBuilder $inlineKeyboard;

  public function __construct(
    TelegramApi $telegramApi,
    UnitDiscovery $unitDiscovery,
    UnitConverterService $converterService,
    InlineKeyboardBuilder $inlineKeyboard,
  ) {
    parent::__construct($telegramApi);
    $this->unitDiscovery = $unitDiscovery;
    $this->converterService = $converterService;
    $this->inlineKeyboard = $inlineKeyboard;
  }

  public function getModuleName(): string
  {
    return 'unitconverter';
  }

  public function getName(): string
  {
    return 'Unit converter callback handler';
  }

  public function handle(array $data, array $context): array
  {
    try {
      return $this->handleCallbackWithAutoAnswer(
        $context,
        $data,
        fn($data, $context) => $this->processCallback($data, $context),
      );
    } catch (\Exception $e) {
      Log::error('UnitConverter: Callback failed', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);

      return [
        'status' => 'callback_failed',
        'answer' => $e->getMessage(),
      ];
    }
  }

  private function processCallback(array $data, array $context): array
  {
    $entity = $data['entity'] ?? '';
    $action = $data['action'] ?? '';
    $id = $data['id'] ?? null;
    $chatId = $context['chat_id'] ?? 0;
    $messageId = $context['message_id'] ?? null;

    return match ($entity) {
      'domain' => $this->handleDomainSelect($action, $id, $chatId, $messageId),
      'from' => $this->handleFromSelect($action, $id, $chatId, $messageId),
      'to' => $this->handleToSelect($action, $id, $chatId, $messageId),
      default => [
        'success' => false,
        'status' => 'unknown_entity',
        'answer' => 'Entity tidak dikenal',
      ],
      };
    }

    // ----- State management dengan cache -----
    private function getState(int $chatId): array
    {
      return Cache::get("unitconv_state_{$chatId}", []);
    }

    private function setState(int $chatId, array $state): void
    {
      Cache::put("unitconv_state_{$chatId}", $state, 3600);
    }

    private function clearState(int $chatId): void
    {
      Cache::forget("unitconv_state_{$chatId}");
    }

    // ----- Helper: tombol kembali -----
    private function backButton(string $entity): array
    {
      // Penting: value diisi string kosong, bukan null
      return [
        [
          'text' => '« Kembali',
          'callback_data' => [
            'action' => 'back',
            'value' => '0'
          ],
        ],
      ];
    }

    // ----- Helper: daftar domain (untuk navigasi kembali) -----
    private function domainListEdit(): array
    {
      $domains = $this->unitDiscovery->getDomains();

      $items = array_map(function ($domain) {
        return [
          'text' => $domain['name'],
          'callback_data' => [
            'action' => 'select',
            'value' => $domain['key'],
          ],
        ];
      }, $domains);

      $this->inlineKeyboard->setModule('unitconverter');
      $this->inlineKeyboard->setEntity('domain');
      $keyboards = $this->inlineKeyboard->grid($items, 3);

      return [
        'text' => "*🔀 Konversi Satuan*\n\nSilakan pilih _domain_ satuan:",
        'parse_mode' => 'MarkdownV2',
        'reply_markup' => ['inline_keyboard' => $keyboards],
      ];
    }

    // ----- handleDomainSelect -----
    private function handleDomainSelect(string $action, string $id, int $chatId, ?int $messageId): array
    {
      if ($action === 'back') {
        $this->clearState($chatId);
        return [
          'success' => true,
          'status' => 'back_to_domains',
          'edit_message' => $this->domainListEdit(),
        ];
      }

      if ($action !== 'select') {
        return ['success' => false,
          'status' => 'invalid_action'];
      }

      $units = $this->unitDiscovery->getUnitsByDomainWithShortIds($id);

      if (empty($units)) {
        return [
          'success' => false,
          'status' => 'no_units',
          'answer' => 'Domain tidak memiliki satuan',
        ];
      }

      $this->setState($chatId, [
        'domain' => $id,
        'fromId' => null,
        'toId' => null,
        'waitingInput' => false,
      ]);

      $this->inlineKeyboard->setModule('unitconverter');
      $this->inlineKeyboard->setEntity('from');

      $items = array_map(function ($unit) {
        return [
          'text' => $unit['symbol'] . ' - ' . $unit['name'],
          'callback_data' => [
            'action' => 'select',
            'value' => $unit['short_id'],
          ],
        ];
      }, $units);

      $keyboards = $this->inlineKeyboard->grid($items, 2);
      $keyboards[] = $this->backButton('domain');

      $message = "*Pilih Satuan Sumber*\n";
      $message .= "Domain: *{$id}*\n";
      $message .= "⸻\n";
      $message .= 'Total: ' . count($units) . ' satuan';

      return [
        'success' => true,
        'status' => 'from_list',
        'edit_message' => [
          'text' => $message,
          'parse_mode' => 'MarkdownV2',
          'reply_markup' => ['inline_keyboard' => $keyboards],
        ],
      ];
    }

    // ----- handleFromSelect -----
    private function handleFromSelect(string $action, string $id, int $chatId, ?int $messageId): array
    {
      $state = $this->getState($chatId);
      $domain = $state['domain'] ?? null;

      if ($action === 'back') {
        $this->clearState($chatId);
        return [
          'success' => true,
          'status' => 'back_to_domains',
          'edit_message' => $this->domainListEdit(),
        ];
      }

      if ($action !== 'select') {
        return ['success' => false,
          'status' => 'invalid_action'];
      }

      if (!$domain) {
        return [
          'success' => false,
          'status' => 'no_domain',
          'answer' => 'Sesi habis, silakan /convert lagi',
        ];
      }

      $realId = $this->unitDiscovery->resolveShortId($domain, $id);
      if (!$realId) {
        return [
          'success' => false,
          'status' => 'invalid_short_id',
          'answer' => 'ID satuan tidak valid',
        ];
      }

      $state['fromId'] = $realId;
      $this->setState($chatId, $state);

      $units = $this->unitDiscovery->getUnitsByDomainWithShortIds($domain);

      $this->inlineKeyboard->setModule('unitconverter');
      $this->inlineKeyboard->setEntity('to');

      $items = array_map(function ($unit) {
        return [
          'text' => $unit['symbol'] . ' - ' . $unit['name'],
          'callback_data' => [
            'action' => 'select',
            'value' => $unit['short_id'],
          ],
        ];
      }, $units);

      $keyboards = $this->inlineKeyboard->grid($items, 2);
      $keyboards[] = $this->backButton('from');

      $fromUnit = $this->unitDiscovery->find($realId);
      $fromLabel = $fromUnit ? $fromUnit['symbol'] . ' (' . $fromUnit['name'] . ')' : $realId;

      $message = "*Pilih Satuan Tujuan*\n";
      $message .= "Dari: *{$fromLabel}*\n";
      $message .= "Domain: *{$domain}*\n";
      $message .= "⸻\n";
      $message .= 'Total: ' . count($units) . ' satuan';

      return [
        'success' => true,
        'status' => 'to_list',
        'edit_message' => [
          'text' => $message,
          'parse_mode' => 'MarkdownV2',
          'reply_markup' => ['inline_keyboard' => $keyboards],
        ],
      ];
    }

    // ----- handleToSelect -----
    private function handleToSelect(string $action, string $id, int $chatId, ?int $messageId): array
    {
      $state = $this->getState($chatId);
      $domain = $state['domain'] ?? null;
      $fromId = $state['fromId'] ?? null;

      if ($action === 'back') {
        if (!$domain) {
          $this->clearState($chatId);
          return [
            'success' => true,
            'status' => 'back_to_domains',
            'edit_message' => $this->domainListEdit(),
          ];
        }

        // Kembali ke daftar from
        $units = $this->unitDiscovery->getUnitsByDomainWithShortIds($domain);
        $this->inlineKeyboard->setModule('unitconverter');
        $this->inlineKeyboard->setEntity('from');

        $items = array_map(function ($unit) {
          return [
            'text' => $unit['symbol'] . ' - ' . $unit['name'],
            'callback_data' => [
              'action' => 'select',
              'value' => $unit['short_id'],
            ],
          ];
        }, $units);

        $keyboards = $this->inlineKeyboard->grid($items, 2);
        $keyboards[] = $this->backButton('domain');

        return [
          'success' => true,
          'status' => 'back_to_from',
          'edit_message' => [
            'text' => "*Pilih Satuan Sumber*\nDomain: *{$domain}*",
            'parse_mode' => 'MarkdownV2',
            'reply_markup' => ['inline_keyboard' => $keyboards],
          ],
        ];
      }

      if ($action !== 'select') {
        return ['success' => false,
          'status' => 'invalid_action'];
      }

      if (!$domain || !$fromId) {
        return [
          'success' => false,
          'status' => 'no_session',
          'answer' => 'Sesi habis, silakan /convert lagi',
        ];
      }

      $realId = $this->unitDiscovery->resolveShortId($domain, $id);
      if (!$realId) {
        return [
          'success' => false,
          'status' => 'invalid_short_id',
          'answer' => 'ID satuan tidak valid',
        ];
      }

      $state['toId'] = $realId;
      $state['waitingInput'] = true;
      $this->setState($chatId, $state);

      $fromUnit = $this->unitDiscovery->find($fromId);
      $toUnit = $this->unitDiscovery->find($realId);

      $fromLabel = $fromUnit ? $fromUnit['symbol'] : $fromId;
      $toLabel = $toUnit ? $toUnit['symbol'] : $realId;

      $message = "*Masukkan Nilai*\n";
      $message .= "Konversi: *{$fromLabel}* → *{$toLabel}*\n";
      $message .= "⸻\n";
      $message .= "Balas pesan ini dengan angka yang ingin dikonversi\\.\n";
      $message .= "_Contoh: `42.5`_";

      return [
        'success' => true,
        'status' => 'waiting_input',
        'edit_message' => [
          'text' => $message,
          'parse_mode' => 'MarkdownV2',
        ],
      ];
    }

    // ----- handleInput (teks angka) -----
    public function handleInput(int $chatId, string $text): ?array
    {
      $state = $this->getState($chatId);

      if (!($state['waitingInput'] ?? false)) {
        return null;
      }

      $fromId = $state['fromId'] ?? null;
      $toId = $state['toId'] ?? null;

      if (!$fromId || !$toId) {
        $this->clearState($chatId);
        return null;
      }

      $value = str_replace(',', '.', trim($text));
      if (!is_numeric($value)) {
        return [
          'status' => 'invalid_number',
          'send_message' => [
            'text' => '⚠️ Masukkan angka yang valid\. Contoh: `42.5`',
            'parse_mode' => 'MarkdownV2',
          ],
        ];
      }

      $value = (float) $value;

      try {
        $result = $this->converterService->convert($value, $fromId, $toId);

        $fromUnit = $this->unitDiscovery->find($fromId);
        $toUnit = $this->unitDiscovery->find($toId);

        $fromLabel = $fromUnit ? $fromUnit['symbol'] : $fromId;
        $toLabel = $toUnit ? $toUnit['symbol'] : $toId;

        $message = "*✅ Hasil Konversi*\n\n";
        $message .= "{$value} {$fromLabel} = *{$result['result']} {$toLabel}*\n";
        $message .= "\n⸻\n";
        $message .= "Kirim angka lagi untuk konversi baru\\.\n";
        $message .= "Ketik /convert untuk ganti satuan\\.";

        $state['waitingInput'] = false;
        $this->setState($chatId, $state);

        return [
          'status' => 'conversion_done',
          'send_message' => [
            'text' => $message,
            'parse_mode' => 'MarkdownV2',
          ],
        ];
      } catch (\Exception $e) {
        return [
          'status' => 'conversion_error',
          'send_message' => [
            'text' => '❌ Gagal konversi: ' . $e->getMessage(),
          ],
        ];
      }
    }

    public function isWaitingInput(int $chatId): bool
    {
      $state = $this->getState($chatId);
      return $state['waitingInput'] ?? false;
    }
  }