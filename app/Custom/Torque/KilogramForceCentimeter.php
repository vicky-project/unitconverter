<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;

class KilogramForceCentimeter extends NewtonMeter implements Metric
{
  const FACTOR = 0.0980665;
  const SYMBOL = 'kgf·cm';
  const LABEL = 'kilogram-force centimeter';
}