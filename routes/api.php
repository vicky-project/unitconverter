<?php

use Illuminate\Support\Facades\Route;
use Modules\UnitConverter\Http\Controllers\Api\UnitController;

Route::middleware(['auth:sanctum'])->prefix('units')->group(function () {
  Route::get('all', [UnitController::class, 'index']);
  Route::get('domains', [UnitController::class, 'domains']);
  Route::get('{domain}', [UnitController::class, 'unitsByDomain']);
  Route::post('convert', [UnitController::class, 'convert']);
});