<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\HasRelativeFactor;

class MegaPascal extends Pascal implements Metric
{
  use HasRelativeFactor;

  const FACTOR = 1000000;
  const SYMBOL = 'mPa';
  const LABEL = 'megapascal';
}