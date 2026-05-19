<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class TeraHertz extends Hertz implements Metric
{
  const FACTOR = 1000000000000;
  const SYMBOL = 'THz';
  const LABEL = 'terahertz';
}