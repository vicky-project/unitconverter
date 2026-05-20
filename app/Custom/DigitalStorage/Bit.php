<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class Bit extends Byte implements Metric
{
  const FACTOR = 0.125; // 1 bit = 1/8 byte
  const SYMBOL = 'b';
  const LABEL = 'bit';
}