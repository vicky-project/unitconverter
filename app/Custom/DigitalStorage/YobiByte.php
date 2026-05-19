<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class YobiByte extends Byte implements Metric
{
  // 2^80 = 1.2089258196146e+24
  const FACTOR = 1.2089258196146e24;
  const SYMBOL = 'YiB';
  const LABEL = 'yobibyte';
}