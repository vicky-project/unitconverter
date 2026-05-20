<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;

class KiloNewtonMeter extends NewtonMeter implements Metric
{
  const FACTOR = 1000;
  const SYMBOL = 'kN·m';
  const LABEL = 'kilonewton meter';
}