<?php

namespace Modules\UnitConverter\Telegram\Replies;

use Modules\Telegram\Services\Handlers\Replies\BaseReplyHandler;
use Modules\Telegram\Services\Support\TelegramApi;
use Modules\UnitConverter\Services\UnitDiscovery;
use Modules\UnitConverter\Services\UnitConverterService;
use Illuminate\Support\Facades\Log;

class InputNumberReplyHandler extends BaseReplyHandler
{
  protected UnitDiscovery $unitDiscovery;
  protected UnitConverterService $converterService;

  public function __construct(
    TelegramApi $telegramApi,
    UnitDiscovery $unitDiscovery,
    UnitConverterService $converterService
  ) {
    parent::__construct($telegramApi);
    $this->unitDiscovery = $unitDiscovery;
    $this->converterService = $converterService;
  }

  public function getModuleName(): string
  {
    return 'unitconverter';
  }

  public function getEntity(): string
  {
    return 'input';
  }

  public function getAction(): string
  {
    return 'number';
  }

  public function handle(
    array $context,
    string $replyText,
    int $chatId,
    int $replyToMessageId
  ): array {
    $fromId = $context['fromId'] ?? null;
    $toId = $context['toId'] ?? null;

    if (!$fromId || !$toId) {
      return [
        'status' => 'invalid_context',
        'send_message' => [
          'text' => '❌ Sesi konversi tidak valid. Silakan mulai ulang dengan /convert.',
          'parse_mode' => 'MarkdownV2',
        ],
      ];
    }

    $value = str_replace(',', '.', trim($replyText));
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
      Log::debug("Accept reply message", [
        'result' => $result,
        'value' => $value
      ]);

      $fromUnit = $this->unitDiscovery->find($fromId);
      $toUnit = $this->unitDiscovery->find($toId);

      $fromLabel = $fromUnit ? $fromUnit['symbol'] : $fromId;
      $toLabel = $toUnit ? $toUnit['symbol'] : $toId;

      $message = "*✅ Hasil Konversi*\n\n";
      $message .= "{$value} {$fromLabel} = *{$result['result']} {$toLabel}*\n";
      $message .= "\n⸻\n";
      $message .= "Kirim angka lagi untuk konversi baru\\.\n";
      $message .= "Ketik /convert untuk ganti satuan\\.";

      return [
        'status' => 'conversion_done',
        'send_message' => [
          'text' => $message,
          'parse_mode' => 'MarkdownV2',
        ],
      ];
    } catch (\Exception $e) {
      Log::error('UnitConverter: Reply conversion failed', [
        'message' => $e->getMessage(),
      ]);

      return [
        'status' => 'conversion_error',
        'send_message' => [
          'text' => '❌ Gagal konversi: ' . $e->getMessage(),
        ],
      ];
    }
  }
}