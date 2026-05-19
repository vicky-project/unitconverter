<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class KibiByte extends Byte implements Metric
{
  const FACTOR = 1024; // 2^10
  const SYMBOL = 'KiB';
  const LABEL = 'kibibyte';
}