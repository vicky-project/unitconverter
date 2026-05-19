<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class MilliPascal extends Pascal implements Metric
{
  const FACTOR = 0.001;
  const SYMBOL = 'mPa';
  const LABEL = 'millipascal';
}