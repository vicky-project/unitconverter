<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class GigaBit extends Byte implements Metric
{
  const FACTOR = 125000000; // 1 Gb = 10^9 bit / 8 = 125000000 byte
  const SYMBOL = 'Gb';
  const LABEL = 'gigabit';
}