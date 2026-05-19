<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class PebiByte extends Byte implements Metric
{
  const FACTOR = 1125899906842624; // 2^50
  const SYMBOL = 'PiB';
  const LABEL = 'pebibyte';
}