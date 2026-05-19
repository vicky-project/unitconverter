<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class MillimeterOfWater extends Pascal implements Metric
{
  const FACTOR = 9.80665;
  const SYMBOL = 'mmH₂O';
  const LABEL = 'millimeter of water';
}