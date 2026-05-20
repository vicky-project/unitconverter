<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\BaseUnit;
use Modules\UnitConverter\Custom\Frequency as BaseFrequency;

class Hertz extends BaseFrequency implements Metric
{
  use BaseUnit;

  const SYMBOL = 'Hz';
  const LABEL = 'hertz';
}