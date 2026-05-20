<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class MebiByte extends Byte implements Metric
{
  const FACTOR = 1048576; // 2^20
  const SYMBOL = 'MiB';
  const LABEL = 'mebibyte';
}