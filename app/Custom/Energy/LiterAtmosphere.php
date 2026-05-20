<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class LiterAtmosphere extends Joule implements Metric
{
  const FACTOR = 101.325;
  const SYMBOL = 'L·atm';
  const LABEL = 'liter-atmosphere';
}