<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\HasRelativeFactor;

class KiloPascal extends Pascal implements Metric
{
  use HasRelativeFactor;

  const FACTOR = 1000;
  const SYMBOL = 'kPa';
  const LABEL = 'kilopascal';
}