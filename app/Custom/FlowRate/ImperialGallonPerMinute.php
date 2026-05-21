<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Imperial;

class ImperialGallonPerMinute extends CubicMeterPerSecond implements Imperial
{
  const FACTOR = 0.00454609 / 60; // 1 IGPM ≈ 7.577×10⁻⁵ m³/s
  const SYMBOL = 'IGPM';
  const LABEL = 'imperial gallon per minute';
}