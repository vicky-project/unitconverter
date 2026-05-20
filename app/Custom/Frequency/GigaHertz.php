<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class GigaHertz extends Hertz implements Metric
{
  const FACTOR = 1000000000;
  const SYMBOL = 'GHz';
  const LABEL = 'gigahertz';
}