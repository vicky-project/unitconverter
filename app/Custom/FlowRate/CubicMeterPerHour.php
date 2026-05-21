<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;

class CubicMeterPerHour extends CubicMeterPerSecond implements Metric
{
  const FACTOR = 1 / 3600; // 1 m³/h = 1/3600 m³/s
  const SYMBOL = 'm³/h';
  const LABEL = 'cubic meter per hour';
}