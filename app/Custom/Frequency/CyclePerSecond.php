<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class CyclePerSecond extends Hertz implements Metric
{
  const FACTOR = 1;
  const SYMBOL = 'cps';
  const LABEL = 'cycle per second';
}