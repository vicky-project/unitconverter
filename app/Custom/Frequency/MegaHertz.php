<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class MegaHertz extends Hertz implements Metric
{
  const FACTOR = 1000000;
  const SYMBOL = 'MHz';
  const LABEL = 'megahertz';
}