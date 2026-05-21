<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;

class LiterPerSecond extends CubicMeterPerSecond implements Metric
{
  const FACTOR = 0.001; // 1 L/s = 0.001 m³/s
  const SYMBOL = 'L/s';
  const LABEL = 'liter per second';
}