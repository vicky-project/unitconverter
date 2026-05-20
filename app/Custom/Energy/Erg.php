<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class Erg extends Joule implements Metric
{
  const FACTOR = 1e-7; // 1 erg = 10^-7 J
  const SYMBOL = 'erg';
  const LABEL = 'erg';
}