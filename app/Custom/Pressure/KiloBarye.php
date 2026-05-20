<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class Kilobarye extends Pascal implements Metric
{
  const FACTOR = 100;
  const SYMBOL = 'kBa';
  const LABEL = 'kilobarye';
}