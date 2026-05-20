<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class MegaByte extends Byte implements Metric
{
  const FACTOR = 1000000; // 10^6
  const SYMBOL = 'MB';
  const LABEL = 'megabyte';
}