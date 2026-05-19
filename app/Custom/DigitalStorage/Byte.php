<?php

namespace Modules\UnitConverter\Custom\DigitalStorage;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\BaseUnit;
use Modules\UnitConverter\Custom\DigitalStorage as BaseDigitalStorage;

class Byte extends BaseDigitalStorage implements Metric
{
  use BaseUnit;

  const SYMBOL = 'B';
  const LABEL = 'byte';
}