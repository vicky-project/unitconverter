<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;

class NewtonCentimeter extends NewtonMeter implements Metric
{
  const FACTOR = 0.01;
  const SYMBOL = 'N·cm';
  const LABEL = 'newton centimeter';
}