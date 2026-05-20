<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Imperial;

class OunceForceFoot extends NewtonMeter implements Imperial
{
  // 1 ozf·ft ≈ 0.084738 N·m
  const FACTOR = 0.084738;
  const SYMBOL = 'ozf·ft';
  const LABEL = 'ounce-force foot';
}