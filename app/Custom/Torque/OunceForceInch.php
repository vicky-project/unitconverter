<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Imperial;

class OunceForceInch extends NewtonMeter implements Imperial
{
  // 1 ozf·in ≈ 0.0070615 N·m
  const FACTOR = 0.0070615;
  const SYMBOL = 'ozf·in';
  const LABEL = 'ounce-force inch';
}