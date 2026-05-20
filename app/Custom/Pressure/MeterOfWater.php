<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class MeterOfWater extends Pascal implements Metric
{
  const FACTOR = 9806.65;
  const SYMBOL = 'mH₂O';
  const LABEL = 'meter of water';
}