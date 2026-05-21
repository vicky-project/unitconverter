<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;

class LiterPerMinute extends CubicMeterPerSecond implements Metric
{
  const FACTOR = 1.6666667e-5; // 1 L/min ≈ 1.67×10⁻⁵ m³/s
  const SYMBOL = 'L/min';
  const LABEL = 'liter per minute';
}