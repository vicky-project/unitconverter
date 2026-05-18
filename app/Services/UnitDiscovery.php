<?php
namespace Modules\UnitConverter\Services;

use Mesura\UnitSystem;

class UnitDiscovery
{
  protected array $units;

  public function __construct() {
    $this->units = config('unitconverter.units', []);
  }

  public function getGroupedBySystem(): array
  {
    $grouped = [];
    foreach ($this->units as $class) {
      $system = $class::unitSystem();
      $systemName = $system->name;
      $grouped[$systemName][] = [
        'id' => $this->makeId($class),
        'name' => $class::getName(),
        'symbol' => $class::getSymbol(),
        'system' => $systemName,
        'class' => $class,
      ];
    }
    ksort($grouped);
    return $grouped;
  }

  public function find(string $id): ?array
  {
    foreach ($this->units as $class) {
      if ($this->makeId($class) === $id) {
        return [
          'id' => $id,
          'name' => $class::getName(),
          'symbol' => $class::getSymbol(),
          'class' => $class,
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
    $base1 = get_class((new $unit1['class'](1))->toBase());
    $base2 = get_class((new $unit2['class'](1))->toBase());
    return $base1 === $base2;
  }

  protected function makeId(string $class): string
  {
    return str_replace('\\', '.', $class);
  }
}