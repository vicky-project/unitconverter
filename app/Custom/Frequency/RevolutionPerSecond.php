<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class RevolutionPerSecond extends Hertz implements Metric
{
  const FACTOR = 1;
  const SYMBOL = 'rps';
  const LABEL = 'revolution per second';
}