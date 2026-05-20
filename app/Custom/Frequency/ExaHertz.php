<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class ExaHertz extends Hertz implements Metric
{
  const FACTOR = 1e18;
  const SYMBOL = 'EHz';
  const LABEL = 'exahertz';
}