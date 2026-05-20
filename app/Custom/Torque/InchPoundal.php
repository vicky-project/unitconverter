<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Imperial;

class InchPoundal extends NewtonMeter implements Imperial
{
  // 1 in·pdl ≈ 0.0035117 N·m
  const FACTOR = 0.0035117;
  const SYMBOL = 'in·pdl';
  const LABEL = 'inch poundal';
}