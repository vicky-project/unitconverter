<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class CentimeterOfMercury extends Pascal implements Metric
{
  const FACTOR = 1333.22;
  const SYMBOL = 'cmHg';
  const LABEL = 'centimeter of mercury';
}