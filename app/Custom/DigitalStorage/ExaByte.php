<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class ExaByte extends Byte implements Metric
{
  const FACTOR = 1000000000000000000; // 10^18
  const SYMBOL = 'EB';
  const LABEL = 'exabyte';
}