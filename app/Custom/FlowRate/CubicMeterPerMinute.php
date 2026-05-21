<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;

class CubicMeterPerMinute extends CubicMeterPerSecond implements Metric
{
  const FACTOR = 1 / 60; // 1 m³/min = 1/60 m³/s
  const SYMBOL = 'm³/min';
  const LABEL = 'cubic meter per minute';
}