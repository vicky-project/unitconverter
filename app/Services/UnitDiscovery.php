<?php

namespace Modules\UnitConverter\Services;

use Illuminate\Support\Facades\Cache;

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
        'name' => $class::LABEL,
        'symbol' => $class::SYMBOL,
        'system' => $system,
        'class' => $class,
      ];
    }
    ksort($grouped);
    return $grouped;
  }

  public function getDomains(): array
  {
    $domains = [];
    foreach ($this->units as $unit) {
      $domain = $this->getDomain($unit['class']);
      if (!isset($domains[$domain])) {
        $domains[$domain] = [
          'key' => $domain,
          'name' => ucfirst($domain),
        ];
      }
    }
    return array_values($domains);
  }

  public function getUnitsByDomain(string $domain): array
  {
    $result = [];
    foreach ($this->units as $unit) {
      if ($this->getDomain($unit['class']) === $domain) {
        $result[] = [
          'id' => $this->makeId($unit['class']),
          'name' => $unit['class']::LABEL,
          'symbol' => $unit['class']::SYMBOL,
          'system' => $unit['system'],
        ];
      }
    }
    return $result;
  }

  /**
  * Get units by domain with short IDs (indeks numerik 0, 1, 2...).
  * Short IDs mapping disimpan di cache untuk di-resolve nanti.
  */
  public function getUnitsByDomainWithShortIds(string $domain): array
  {
    $units = $this->getUnitsByDomain($domain);
    $mapping = [];
    $result = [];

    foreach ($units as $index => $unit) {
      $shortId = (string) $index;
      $mapping[$shortId] = $unit['id'];
      $result[] = array_merge($unit, ['short_id' => $shortId]);
    }

    Cache::put("unitconv_short_{$domain}", $mapping, 3600);

    return $result;
  }

  /**
  * Resolve short ID ke real ID berdasarkan domain.
  */
  public function resolveShortId(string $domain, string $shortId): ?string
  {
    $mapping = Cache::get("unitconv_short_{$domain}", []);
    return $mapping[$shortId] ?? null;
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
    if (!$unit1 || !$unit2) {
      return false;
    }
    return $this->getDomain($unit1['class']) === $this->getDomain($unit2['class']);
  }

  protected function getDomain(string $class): string
  {
    $parts = explode('\\', $class);
    if (isset($parts[2]) && $parts[2] === 'Custom') {
      return $parts[3] ?? '';
    }
    return $parts[2] ?? '';
  }

  protected function makeId(string $class): string
  {
    return str_replace('\\', '.', $class);
  }
}