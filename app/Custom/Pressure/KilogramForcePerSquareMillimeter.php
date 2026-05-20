<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class KilogramForcePerSquareMillimeter extends Pascal implements Metric
{
  const FACTOR = 9806650;
  const SYMBOL = 'kgf/mm²';
  const LABEL = 'kilogram-force per square millimeter';
}