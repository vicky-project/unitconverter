<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class MillimeterOfMercury extends Pascal implements Metric
{
  const FACTOR = 133.322;
  const SYMBOL = 'mmHg';
  const LABEL = 'millimeter of mercury';
}