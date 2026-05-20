<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class TeraByte extends Byte implements Metric
{
  const FACTOR = 1000000000000; // 10^12
  const SYMBOL = 'TB';
  const LABEL = 'terabyte';
}