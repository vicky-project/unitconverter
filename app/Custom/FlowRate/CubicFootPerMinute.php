<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Imperial;

class CubicFootPerMinute extends CubicMeterPerSecond implements Imperial
{
  const FACTOR = 0.0283168 / 60; // 1 CFM ≈ 0.000471947 m³/s
  const SYMBOL = 'CFM';
  const LABEL = 'cubic foot per minute';
}