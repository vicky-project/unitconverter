<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;

class KilogramForceMeter extends NewtonMeter implements Metric
{
  const FACTOR = 9.80665;
  const SYMBOL = 'kgf·m';
  const LABEL = 'kilogram-force meter';
}