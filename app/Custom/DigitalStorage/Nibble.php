<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class Nibble extends Byte implements Metric
{
  const FACTOR = 0.5; // 1 nibble = 4 bits = 0.5 byte
  const SYMBOL = 'nibble';
  const LABEL = 'nibble';
}