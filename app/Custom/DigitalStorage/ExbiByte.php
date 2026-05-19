<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class ExbiByte extends Byte implements Metric
{
  const FACTOR = 1152921504606846976; // 2^60
  const SYMBOL = 'EiB';
  const LABEL = 'exbibyte';
}