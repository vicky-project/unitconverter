<?php
namespace Modules\UnitConverter\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\UnitConverter\Services\UnitDiscovery;
use Modules\UnitConverter\Services\UnitConverterService;

class UnitController extends Controller
{
  public function __construct(
    protected UnitDiscovery $discovery,
    protected UnitConverterService $converter
  ) {}

  public function index(): JsonResponse
  {
    $grouped = $this->discovery->getGroupedBySystem();
    // Sederhanakan output, hapus 'class'
    $data = [];
    foreach ($grouped as $system => $units) {
      $data[$system] = array_map(fn($u) => [
        'id' => $u['id'],
        'name' => $u['name'],
        'symbol' => $u['symbol'],
        'system' => $u['system'],
      ], $units);
    }
    return response()->json(['data' => $data]);
  }

  public function convert(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'value' => 'required|numeric',
      'from' => 'required|string',
      'to' => 'required|string',
    ]);

    try {
      $result = $this->converter->convert(
        $validated['value'],
        $validated['from'],
        $validated['to']
      );
      return response()->json(['data' => $result]);
    } catch (\InvalidArgumentException $e) {
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }

  public function domains(): JsonResponse
  {
    return response()->json(['data' => $this->discovery->getDomains()]);
  }

  public function unitsByDomain(string $domain): JsonResponse
  {
    $units = $this->discovery->getUnitsByDomain($domain);
    if (empty($units)) {
      return response()->json(['error' => 'Domain not found.'], 404);
    }
    return response()->json(['data' => $units]);
  }
}