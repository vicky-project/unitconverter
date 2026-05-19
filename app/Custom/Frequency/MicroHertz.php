<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class MicroHertz extends Hertz implements Metric
{
  const FACTOR = 0.000001;
  const SYMBOL = 'µHz';
  const LABEL = 'microhertz';
}