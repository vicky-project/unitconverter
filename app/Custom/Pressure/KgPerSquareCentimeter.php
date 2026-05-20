<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class KgPerSquareCentimeter extends Pascal implements Metric
{
  const FACTOR = 98066.5;
  const SYMBOL = 'kg/cm²';
  const LABEL = 'kilogram per square centimeter';
}