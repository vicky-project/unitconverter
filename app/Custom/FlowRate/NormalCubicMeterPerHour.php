<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;

class NormalCubicMeterPerHour extends CubicMeterPerSecond implements Metric
{
  // 1 Nm³/h = 1/3600 m³/s ≈ 0.0002777777778
  const FACTOR = 1 / 3600;
  const SYMBOL = 'Nm³/h';
  const LABEL = 'normal cubic meter per hour';
}