<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;

class GramForceCentimeter extends NewtonMeter implements Metric
{
  const FACTOR = 0.0000980665;
  const SYMBOL = 'gf·cm';
  const LABEL = 'gram-force centimeter';
}