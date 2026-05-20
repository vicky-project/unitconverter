<?php

namespace Modules\UnitConverter\Telegram;

use Illuminate\Support\Facades\Log;
use Modules\UnitConverter\Services\UnitDiscovery;
use Modules\Telegram\Services\Support\InlineKeyboardBuilder;
use Modules\Telegram\Services\Support\TelegramApi;
use Modules\Telegram\Services\Handlers\Commands\BaseCommandHandler;

class ConvertCommand extends BaseCommandHandler
{
  protected UnitDiscovery $unitDiscovery;
  protected InlineKeyboardBuilder $inlineKeyboard;

  public function __construct(
    TelegramApi $telegram,
    UnitDiscovery $unitDiscovery,
    InlineKeyboardBuilder $inlineKeyboard,
  ) {
    parent::__construct($telegram);
    $this->unitDiscovery = $unitDiscovery;
    $this->inlineKeyboard = $inlineKeyboard;
  }

  public function getName(): string
  {
    return 'convert';
  }

  public function getDescription(): string
  {
    return 'Konversi satuan presisi tinggi';
  }

  protected function processCommand(
    int $chatId,
    string $text,
    ?string $username = null,
    array $params = [],
  ): array {
    try {
      $domains = $this->unitDiscovery->getDomains();

      if (empty($domains)) {
        return [
          'status' => 'no_domains',
          'send_message' => [
            'text' => '⚠️ Tidak ada domain satuan tersedia.',
          ],
        ];
      }

      $message = "*🔀 Konversi Satuan*\n\n";
      $message .= "Silakan pilih _domain_ satuan:\n";
      $message .= "⸻\n";
      $message .= "📊 Total: " . count($domains) . " domain";

      $keyboards = $this->prepareDomainKeyboard($domains);

      return [
        'status' => 'domain_list_sent',
        'count' => count($domains),
        'send_message' => [
          'text' => $message,
          'parse_mode' => 'MarkdownV2',
          'reply_markup' => ['inline_keyboard' => $keyboards],
        ],
      ];
    } catch (\Exception $e) {
      Log::error('UnitConverter: Failed to show domains', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);

      return [
        'status' => 'error',
        'send_message' => [
          'text' => '❌ Gagal memuat daftar domain: ' . $e->getMessage(),
        ],
      ];
    }
  }

  private function prepareDomainKeyboard(array $domains): array
  {
    $this->inlineKeyboard->setModule('unitconverter');
    $this->inlineKeyboard->setEntity('domain');

    $items = array_map(function ($domain) {
      return [
        'text' => $domain['name'],
        'callback_data' => [
          'action' => 'select',
          'value' => $domain['key'],
        ],
      ];
    }, $domains);

    return $this->inlineKeyboard->grid($items, 3);
  }
}