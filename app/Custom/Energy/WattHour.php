<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class WattHour extends Joule implements Metric
{
  const FACTOR = 3600;
  const SYMBOL = 'Wh';
  const LABEL = 'watt hour';
}