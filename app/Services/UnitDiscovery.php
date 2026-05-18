<?php

namespace Modules\UnitConverter\Services;

class UnitDiscovery
{
  protected array $units;

  public function __construct() {
    $this->units = config('unitconverter.units', []);
  }

  /**
  * Get all units grouped by system.
  */
  public function getGroupedBySystem(): array
  {
    $grouped = [];
    foreach ($this->units as $unit) {
      $class = $unit['class'];
      $system = $unit['system'];

      $grouped[$system][] = [
        'id' => $this->makeId($class),
        'name' => $class::LABEL,
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
          'name' => $unit['class']::LABEL,
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

    return $this->getDomain($unit1['class']) === $this->getDomain($unit2['class']);
  }

  protected function getDomain(string $class): string
  {
    // PhpUnitConversion\Unit\Area\Acre → Area
    $parts = explode('\\', $class);
    return $parts[3] ?? '';
  }

  protected function makeId(string $class): string
  {
    return str_replace('\\', '.', $class);
  }
}