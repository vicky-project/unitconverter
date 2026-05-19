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
        'name' => (new $class())->getLabel(),
        'symbol' => (new $class())->getSymbol(),
        'system' => $system,
        'class' => $class,
      ];
    }
    ksort($grouped);
    return $grouped;
  }

  /**
  * Get list of unique domains with their names.
  */
  public function getDomains(): array
  {
    $domains = [];
    foreach ($this->units as $unit) {
      $domain = $this->getDomain($unit['class']);
      if (!isset($domains[$domain])) {
        $domains[$domain] = [
          'key' => $domain,
          'name' => ucfirst($domain),
          // bisa di-custom nanti
        ];
      }
    }
    return array_values($domains);
  }

  /**
  * Get units by domain.
  */
  public function getUnitsByDomain(string $domain): array
  {
    $result = [];
    foreach ($this->units as $unit) {
      if ($this->getDomain($unit['class']) === $domain) {
        $result[] = [
          'id' => $this->makeId($unit['class']),
          'name' => (new $unit['class']())->getLabel(),
          'symbol' => (new $unit['class']())->getSymbol(),
          'system' => $unit['system'],
        ];
      }
    }
    return $result;
  }

  public function find(string $id): ?array
  {
    foreach ($this->units as $unit) {
      if ($this->makeId($unit['class']) === $id) {
        return [
          'id' => $id,
          'name' => (new $unit['class']())->getLabel(),
          'symbol' => (new $unit['class']())->getSymbol(),
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
    if (!$unit1 || !$unit2) {
      return false;
    }

    return $this->getDomain($unit1['class']) === $this->getDomain($unit2['class']);
  }

  protected function getDomain(string $class): string
  {
    // PhpUnitConversion\Unit\Area\Acre → Area
    $parts = explode('\\', $class);
    return $parts[2] ?? '';
  }

  protected function makeId(string $class): string
  {
    return str_replace('\\', '.', $class);
  }
}