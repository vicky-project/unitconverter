<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class Bar extends Pascal implements Metric
{
  const FACTOR = 100000;
  const SYMBOL = 'bar';
  const LABEL = 'bar';
}