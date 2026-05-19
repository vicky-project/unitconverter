<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class TechnicalAtmosphere extends Pascal implements Metric
{
  const FACTOR = 98066.5;
  const SYMBOL = 'at';
  const LABEL = 'technical atmosphere';
}