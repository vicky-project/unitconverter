<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class ZettaByte extends Byte implements Metric
{
  const FACTOR = 1000000000000000000000; // 10^21
  const SYMBOL = 'ZB';
  const LABEL = 'zettabyte';
}