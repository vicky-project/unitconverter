<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;

class DyneCentimeter extends NewtonMeter implements Metric
{
  // 1 dyn·cm = 10^-7 N·m
  const FACTOR = 1e-7;
  const SYMBOL = 'dyn·cm';
  const LABEL = 'dyne centimeter';
}