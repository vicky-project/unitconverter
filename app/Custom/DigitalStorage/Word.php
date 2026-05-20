<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class Word extends Byte implements Metric
{
  const FACTOR = 2; // 1 word = 2 byte
  const SYMBOL = 'word';
  const LABEL = 'word';
}