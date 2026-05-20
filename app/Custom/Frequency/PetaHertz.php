<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class PetaHertz extends Hertz implements Metric
{
  const FACTOR = 1e15;
  const SYMBOL = 'PHz';
  const LABEL = 'petahertz';
}