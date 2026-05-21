<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Imperial;

class CubicFootPerHour extends CubicMeterPerSecond implements Imperial
{
  const FACTOR = 0.0283168 / 3600; // 1 ft³/h
  const SYMBOL = 'ft³/h';
  const LABEL = 'cubic foot per hour';
}