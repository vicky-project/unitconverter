<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;

class LiterPerHour extends CubicMeterPerSecond implements Metric
{
  const FACTOR = 2.7777778e-7; // 1 L/h ≈ 2.78×10⁻⁷ m³/s
  const SYMBOL = 'L/h';
  const LABEL = 'liter per hour';
}