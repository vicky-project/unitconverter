<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class KilogramForcePerSquareMeter extends Pascal implements Metric
{
  const FACTOR = 9.80665;
  const SYMBOL = 'kgf/m²';
  const LABEL = 'kilogram-force per square meter';
}