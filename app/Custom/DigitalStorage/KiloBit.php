<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class KiloBit extends Byte implements Metric
{
  const FACTOR = 125; // 1 kb = 1000 bit / 8 = 125 byte
  const SYMBOL = 'kb';
  const LABEL = 'kilobit';
}