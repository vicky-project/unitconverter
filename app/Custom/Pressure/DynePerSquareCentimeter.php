<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class DynePerSquareCentimeter extends Pascal implements Metric
{
  const FACTOR = 0.1;
  const SYMBOL = 'dyn/cm²';
  const LABEL = 'dyne per square centimeter';
}