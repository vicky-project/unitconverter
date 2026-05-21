<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;

class MilliliterPerSecond extends CubicMeterPerSecond implements Metric
{
  const FACTOR = 1e-6; // 1 mL/s = 10⁻⁶ m³/s
  const SYMBOL = 'mL/s';
  const LABEL = 'milliliter per second';
}