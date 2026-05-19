<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\BaseUnit;
use Modules\UnitConverter\Custom\Pressure as BasePressure;

class Pascal extends BasePressure implements Metric
{
  use BaseUnit;

  const SYMBOL = 'Pa';
  const LABEL = 'pascal';
}