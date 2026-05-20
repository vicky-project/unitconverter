<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Imperial;

class FootPoundal extends NewtonMeter implements Imperial
{
  // 1 ft·pdl ≈ 0.04214 N·m
  const FACTOR = 0.04214;
  const SYMBOL = 'ft·pdl';
  const LABEL = 'foot poundal';
}