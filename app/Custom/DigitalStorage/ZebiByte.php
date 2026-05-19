<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class ZebiByte extends Byte implements Metric
{
  // 2^70 = 1.1805916207174e+21
  const FACTOR = 1.1805916207174e21;
  const SYMBOL = 'ZiB';
  const LABEL = 'zebibyte';
}