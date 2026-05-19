<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class CentimeterOfWater extends Pascal implements Metric
{
  const FACTOR = 98.0665;
  const SYMBOL = 'cmH₂O';
  const LABEL = 'centimeter of water';
}