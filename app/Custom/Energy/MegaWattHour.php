<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class MegaWattHour extends Joule implements Metric
{
  const FACTOR = 3.6e9; // 1 MWh = 1000 kWh = 3.6e9 J
  const SYMBOL = 'MWh';
  const LABEL = 'megawatt hour';
}