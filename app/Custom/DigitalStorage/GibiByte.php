<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class GibiByte extends Byte implements Metric
{
  const FACTOR = 1073741824; // 2^30
  const SYMBOL = 'GiB';
  const LABEL = 'gibibyte';
}