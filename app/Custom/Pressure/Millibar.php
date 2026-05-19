<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class Millibar extends Pascal implements Metric
{
  const FACTOR = 100;
  const SYMBOL = 'mbar';
  const LABEL = 'millibar';
}