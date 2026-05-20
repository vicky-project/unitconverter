<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class Thermie extends Joule implements Metric
{
  // 1 thermie = 4.1868e6 J (IT)
  const FACTOR = 4.1868e6;
  const SYMBOL = 'th';
  const LABEL = 'thermie';
}