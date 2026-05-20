<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Imperial;

class PoundForceInch extends NewtonMeter implements Imperial
{
  // 1 lbf·in ≈ 0.112985 N·m
  const FACTOR = 0.112985;
  const SYMBOL = 'lbf·in';
  const LABEL = 'pound-force inch';
}