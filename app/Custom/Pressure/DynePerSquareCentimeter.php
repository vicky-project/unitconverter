<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\CGS;

class DynePerSquareCentimeter extends Pascal implements CGS
{
  const FACTOR = 0.1;
  const SYMBOL = 'dyn/cm²';
  const LABEL = 'dyne per square centimeter';
}