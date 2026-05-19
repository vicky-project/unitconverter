<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class GigaByte extends Byte implements Metric
{
  const FACTOR = 1000000000; // 10^9
  const SYMBOL = 'GB';
  const LABEL = 'gigabyte';
}