<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\BaseUnit;
use Modules\UnitConverter\Custom\Energy as BaseEnergy;

class Joule extends BaseEnergy implements Metric
{
  use BaseUnit;

  const SYMBOL = 'J';
  const LABEL = 'joule';
}