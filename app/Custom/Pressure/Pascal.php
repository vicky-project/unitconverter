<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\Unit;
use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\BaseUnit;

class Pascal extends Unit implements Metric
{
  use BaseUnit;

  const FACTOR = 1.0;
  const SYMBOL = 'Pa';
  const LABEL = 'pascal';
}