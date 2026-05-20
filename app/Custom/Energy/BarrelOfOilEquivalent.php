<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class BarrelOfOilEquivalent extends Joule implements Metric
{
  const FACTOR = 6.12e9;
  const SYMBOL = 'boe';
  const LABEL = 'barrel of oil equivalent';
}