<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class Barye extends Pascal implements Metric
{
  const FACTOR = 0.1;
  const SYMBOL = 'Ba';
  const LABEL = 'barye';
}