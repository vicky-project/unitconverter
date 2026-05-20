<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class TebiByte extends Byte implements Metric
{
  const FACTOR = 1099511627776; // 2^40
  const SYMBOL = 'TiB';
  const LABEL = 'tebibyte';
}