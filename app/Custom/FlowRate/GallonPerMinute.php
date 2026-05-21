<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\USC;

class GallonPerMinute extends CubicMeterPerSecond implements USC
{
  const FACTOR = 0.00378541 / 60; // 1 GPM ≈ 6.309×10⁻⁵ m³/s
  const SYMBOL = 'GPM';
  const LABEL = 'gallon per minute';
}