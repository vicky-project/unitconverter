<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class NanoHertz extends Hertz implements Metric
{
  const FACTOR = 0.000000001;
  const SYMBOL = 'nHz';
  const LABEL = 'nanohertz';
}