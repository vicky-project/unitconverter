<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Imperial;

class PoundForceFoot extends NewtonMeter implements Imperial
{
  // 1 lbf·ft ≈ 1.35582 N·m
  const FACTOR = 1.35582;
  const SYMBOL = 'lbf·ft';
  const LABEL = 'pound-force foot';
}