<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class KilogramForcePerSquareCentimeter extends Pascal implements Metric
{
  const FACTOR = 98066.5;
  const SYMBOL = 'kgf/cm²';
  const LABEL = 'kilogram-force per square centimeter';
}