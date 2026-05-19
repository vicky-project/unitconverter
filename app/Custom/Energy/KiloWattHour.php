<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class KiloWattHour extends Joule implements Metric
{
  const FACTOR = 3600000;
  const SYMBOL = 'kWh';
  const LABEL = 'kilowatt hour';
}