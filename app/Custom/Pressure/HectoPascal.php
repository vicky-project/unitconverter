<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\HasRelativeFactor;

class HectoPascal extends Pascal implements Metric
{
  use HasRelativeFactor;

  const FACTOR = 100;
  const SYMBOL = 'hPa';
  const LABEL = 'hectopascal';
}