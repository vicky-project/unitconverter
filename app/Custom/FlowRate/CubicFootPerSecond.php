<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Imperial;

class CubicFootPerSecond extends CubicMeterPerSecond implements Imperial
{
  const FACTOR = 0.0283168; // 1 ft³/s ≈ 0.0283168 m³/s
  const SYMBOL = 'ft³/s';
  const LABEL = 'cubic foot per second';
}