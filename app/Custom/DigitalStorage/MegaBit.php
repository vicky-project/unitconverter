<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class MegaBit extends Byte implements Metric
{
  const FACTOR = 125000; // 1 Mb = 10^6 bit / 8 = 125000 byte
  const SYMBOL = 'Mb';
  const LABEL = 'megabit';
}