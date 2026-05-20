<?php

namespace Modules\UnitConverter\Custom\Torque;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\BaseUnit;
use Modules\UnitConverter\Custom\Torque as BaseTorque;

class NewtonMeter extends BaseTorque implements Metric
{
  use BaseUnit;

  const SYMBOL = 'N·m';
  const LABEL = 'newton meter';
}