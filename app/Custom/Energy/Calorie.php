<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class Calorie extends Joule implements Metric
{
  const FACTOR = 4.184;
  const SYMBOL = 'cal';
  const LABEL = 'calorie';
}