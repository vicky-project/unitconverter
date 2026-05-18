<?php

use Illuminate\Support\Facades\Route;
use Modules\UnitConverter\Http\Controllers\UnitConverterController;

Route::middleware(['auth:sanctum'])->prefix('apps')->group(function () {
  Route::get('unit-converter', [UnitConverterController::class, 'index']);
});