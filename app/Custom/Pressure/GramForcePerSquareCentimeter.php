<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class GramForcePerSquareCentimeter extends Pascal implements Metric
{
  const FACTOR = 98.0665;
  const SYMBOL = 'gf/cm²';
  const LABEL = 'gram-force per square centimeter';
}