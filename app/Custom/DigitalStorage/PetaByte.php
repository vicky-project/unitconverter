<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;

class PetaByte extends Byte implements Metric
{
  const FACTOR = 1000000000000000; // 10^15
  const SYMBOL = 'PB';
  const LABEL = 'petabyte';
}