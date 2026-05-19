<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class KiloCalorie extends Joule implements Metric
{
  const FACTOR = 4184;
  const SYMBOL = 'kcal';
  const LABEL = 'kilocalorie';
}