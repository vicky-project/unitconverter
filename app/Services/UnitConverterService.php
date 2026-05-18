<?php

namespace Modules\UnitConverter\Services;

class UnitConverterService
{
  public function __construct(protected UnitDiscovery $discovery) {}

  public function convert($value, string $fromId, string $toId): array
  {
    $fromUnit = $this->discovery->find($fromId);
    $toUnit = $this->discovery->find($toId);

    if (!$fromUnit || !$toUnit) {
      throw new \InvalidArgumentException("Unit not found.");
    }
    if (!$this->discovery->sameDomain($fromId, $toId)) {
      throw new \InvalidArgumentException("Cannot convert between different domains.");
    }

    $fromClass = $fromUnit['class'];
    $toClass = $toUnit['class'];

    $measurement = new $fromClass($value);
    $result = $measurement->to($toClass); // float

    return [
      'value' => $value,
      'from' => $fromId,
      'to' => $toId,
      'result' => $result,
      // float, tetapi bisa kita format presisi jika perlu
    ];
  }
}