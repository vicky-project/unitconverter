<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class GigaWattHour extends Joule implements Metric
{
  const FACTOR = 3.6e12; // 1 GWh = 3.6e12 J
  const SYMBOL = 'GWh';
  const LABEL = 'gigawatt hour';
}