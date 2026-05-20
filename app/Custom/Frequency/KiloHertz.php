<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\HasRelativeFactor;

class KiloHertz extends Hertz implements Metric
{
  use HasRelativeFactor;

  const FACTOR = 1000;
  const SYMBOL = 'kHz';
  const LABEL = 'kilohertz';
}