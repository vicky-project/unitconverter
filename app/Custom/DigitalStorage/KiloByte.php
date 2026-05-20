<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class KiloByte extends Byte implements Metric
{
  const FACTOR = 1000; // 10^3
  const SYMBOL = 'KB';
  const LABEL = 'kilobyte';
}