<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\BaseUnit;
use Modules\UnitConverter\Custom\Pressure;

class Pascal extends Pressure implements Metric
{
  use BaseUnit;

  const SYMBOL = 'Pa';
  const LABEL = 'pascal';
}