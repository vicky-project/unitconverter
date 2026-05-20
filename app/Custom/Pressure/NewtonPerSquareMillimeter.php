<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class NewtonPerSquareMillimeter extends Pascal implements Metric
{
  const FACTOR = 1000000;
  const SYMBOL = 'N/mm²';
  const LABEL = 'newton per square millimeter';
}