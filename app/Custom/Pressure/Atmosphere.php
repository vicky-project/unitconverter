<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class Atmosphere extends Pascal implements Metric
{
  const FACTOR = 101325;
  const SYMBOL = 'atm';
  const LABEL = 'atmosphere';
}