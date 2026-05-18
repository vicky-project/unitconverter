<?php

use Illuminate\Support\Facades\Route;
use Modules\UnitConverter\Http\Controllers\Api\UnitController;

Route::middleware(['auth:sanctum'])->prefix('units')->group(function () {
  Route::get('all', [UnitController::class, 'index']);
  Route::get('convert', [UnitController::class, 'convert']);
});