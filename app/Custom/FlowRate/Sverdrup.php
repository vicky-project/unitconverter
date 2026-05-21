<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;

class Sverdrup extends CubicMeterPerSecond implements Metric
{
  // 1 Sv = 1e6 m³/s
  const FACTOR = 1e6;
  const SYMBOL = 'Sv';
  const LABEL = 'sverdrup';
}