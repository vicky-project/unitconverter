<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class Kilobarye extends Pascal implements Metric
{
  const FACTOR = 100; // 1 kBa = 100 Pa
  const SYMBOL = 'kBa';
  const LABEL = 'kilobarye';
}