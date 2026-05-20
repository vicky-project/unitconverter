<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class Decibar extends Pascal implements Metric
{
  const FACTOR = 10000;
  const SYMBOL = 'dbar';
  const LABEL = 'decibar';
}