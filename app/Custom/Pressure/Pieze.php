<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class Pieze extends Pascal implements Metric
{
  const FACTOR = 1000;
  const SYMBOL = 'pz';
  const LABEL = 'pieze';
}