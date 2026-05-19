<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class YottaByte extends Byte implements Metric
{
  const FACTOR = 1000000000000000000000000; // 10^24
  const SYMBOL = 'YB';
  const LABEL = 'yottabyte';
}