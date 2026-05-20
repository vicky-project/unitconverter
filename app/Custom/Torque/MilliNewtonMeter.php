<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;

class MilliNewtonMeter extends NewtonMeter implements Metric
{
  const FACTOR = 0.001;
  const SYMBOL = 'mN·m';
  const LABEL = 'millinewton meter';
}