<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;

class MeganewtonMeter extends NewtonMeter implements Metric
{
  const FACTOR = 1000000;
  const SYMBOL = 'MN·m';
  const LABEL = 'meganewton meter';
}