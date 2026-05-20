<?php

namespace Modules\UnitConverter\Telegram;

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

  // State user: menyimpan fromId per chatId
  protected array $userState = [];

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

    /**
    * User memilih domain → tampilkan satuan "from"
    */
    private function handleDomainSelect(string $action, string $id, int $chatId, ?int $messageId): array
    {
      if ($action !== 'select') {
        return ['success' => false,
          'status' => 'invalid_action'];
      }

      $units = $this->unitDiscovery->getUnitsByDomain($id);

      if (empty($units)) {
        return [
          'success' => false,
          'status' => 'no_units',
          'answer' => 'Domain tidak memiliki satuan',
        ];
      }

      // Reset state user
      $this->userState[$chatId] = [
        'domain' => $id,
        'fromId' => null,
      ];

      $this->inlineKeyboard->setModule('unitconverter');
      $this->inlineKeyboard->setEntity('from');

      $items = array_map(function ($unit) {
        return [
          'text' => $unit['symbol'] . ' - ' . $unit['name'],
          'callback_data' => [
            'entity' => 'from',
            'action' => 'select',
            'id' => $unit['id'],
          ],
        ];
      }, $units);

      $keyboards = $this->inlineKeyboard->grid($items, 2);

      // Tambahkan tombol kembali
      $keyboards[] = [[
        'text' => '« Kembali',
        'callback_data' => json_encode([
          'entity' => 'domain',
          'action' => 'back',
        ]),
      ]];

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

    /**
    * User memilih "from" → tampilkan satuan "to"
    */
    private function handleFromSelect(string $action, string $id, int $chatId, ?int $messageId): array
    {
      if ($action !== 'select') {
        return ['success' => false,
          'status' => 'invalid_action'];
      }

      $domain = $this->userState[$chatId]['domain'] ?? null;
      if (!$domain) {
        return [
          'success' => false,
          'status' => 'no_domain',
          'answer' => 'Sesi habis, silakan /convert lagi',
        ];
      }

      // Simpan fromId
      $this->userState[$chatId]['fromId'] = $id;

      $units = $this->unitDiscovery->getUnitsByDomain($domain);

      $this->inlineKeyboard->setModule('unitconverter');
      $this->inlineKeyboard->setEntity('to');

      $items = array_map(function ($unit) {
        return [
          'text' => $unit['symbol'] . ' - ' . $unit['name'],
          'callback_data' => [
            'entity' => 'to',
            'action' => 'select',
            'id' => $unit['id'],
          ],
        ];
      }, $units);

      $keyboards = $this->inlineKeyboard->grid($items, 2);

      // Tombol kembali
      $keyboards[] = [[
        'text' => '« Kembali',
        'callback_data' => json_encode([
          'entity' => 'from',
          'action' => 'back',
        ]),
      ]];

      // Ambil nama unit from
      $fromUnit = $this->unitDiscovery->find($id);
      $fromLabel = $fromUnit ? $fromUnit['symbol'] . ' (' . $fromUnit['name'] . ')' : $id;

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

    /**
    * User memilih "to" → minta input angka
    */
    private function handleToSelect(string $action, string $id, int $chatId, ?int $messageId): array
    {
      if ($action !== 'select') {
        return ['success' => false,
          'status' => 'invalid_action'];
      }

      $state = $this->userState[$chatId] ?? [];
      $fromId = $state['fromId'] ?? null;

      if (!$fromId) {
        return [
          'success' => false,
          'status' => 'no_from',
          'answer' => 'Sesi habis, silakan /convert lagi',
        ];
      }

      // Simpan toId dan set state menunggu input angka
      $this->userState[$chatId]['toId'] = $id;
      $this->userState[$chatId]['waitingInput'] = true;

      $fromUnit = $this->unitDiscovery->find($fromId);
      $toUnit = $this->unitDiscovery->find($id);

      $fromLabel = $fromUnit ? $fromUnit['symbol'] : $fromId;
      $toLabel = $toUnit ? $toUnit['symbol'] : $id;

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

    /**
    * Handle text message (input angka) untuk konversi yang sedang menunggu
    * Method ini akan dipanggil dari luar saat user mengirim pesan teks
    */
    public function handleInput(int $chatId, string $text): ?array
    {
      $state = $this->userState[$chatId] ?? [];

      if (!($state['waitingInput'] ?? false)) {
        return null; // tidak dalam mode menunggu input
      }

      $fromId = $state['fromId'] ?? null;
      $toId = $state['toId'] ?? null;

      if (!$fromId || !$toId) {
        $this->userState[$chatId] = [];
        return null;
      }

      // Parse angka
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

      // Lakukan konversi
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

        // Reset waiting input, tetap simpan fromId dan toId
        $this->userState[$chatId]['waitingInput'] = false;

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

    /**
    * Cek apakah user sedang dalam mode menunggu input
    */
    public function isWaitingInput(int $chatId): bool
    {
      return $this->userState[$chatId]['waitingInput'] ?? false;
    }
  }