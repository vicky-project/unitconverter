<?php

namespace Modules\UnitConverter\Services;

class UnitDiscovery
{
  protected array $units;

  public function __construct() {
    $this->units = config('unitconverter.units', []);
  }

  public function getGroupedBySystem(): array
  {
    $grouped = [];
    foreach ($this->units as $unit) {
      $class = $unit['class'];
      $system = $unit['system'];

      $grouped[$system][] = [
        'id' => $this->makeId($class),
        'name' => $class::NAME,
        'symbol' => $class::SYMBOL,
        'system' => $system,
        'class' => $class,
      ];
    }
    ksort($grouped);
    return $grouped;
  }

  public function find(string $id): ?array
  {
    foreach ($this->units as $unit) {
      if ($this->makeId($unit['class']) === $id) {
        return [
          'id' => $id,
          'name' => $unit['class']::NAME,
          'symbol' => $unit['class']::SYMBOL,
          'class' => $unit['class'],
        ];
      }
    }
    return null;
  }

  public function sameDomain(string $id1, string $id2): bool
  {
    $unit1 = $this->find($id1);
    $unit2 = $this->find($id2);
    if (!$unit1 || !$unit2) return false;

    // Bandingkan base unit class (misal: PhpUnitConversion\Unit\Length\Meter, ...)
    return $unit1['class']::getBaseUnit() === $unit2['class']::getBaseUnit();
  }

  protected function makeId(string $class): string
  {
    return str_replace('\\', '.', $class);
  }
}