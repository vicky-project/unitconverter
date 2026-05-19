<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\HasRelativeFactor;

class KiloJoule extends Joule implements Metric
{
  use HasRelativeFactor;

  const FACTOR = 1000;
  const SYMBOL = 'kJ';
  const LABEL = 'kilojoule';
}