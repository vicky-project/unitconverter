<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class MilliHertz extends Hertz implements Metric
{
  const FACTOR = 0.001;
  const SYMBOL = 'mHz';
  const LABEL = 'millihertz';
}