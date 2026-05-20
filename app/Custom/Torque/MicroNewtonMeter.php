<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;

class MicroNewtonMeter extends NewtonMeter implements Metric
{
  const FACTOR = 0.000001;
  const SYMBOL = 'µN·m';
  const LABEL = 'micronewton meter';
}