<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class NewtonPerSquareMeter extends Pascal implements Metric
{
  const FACTOR = 1;
  const SYMBOL = 'N/m²';
  const LABEL = 'newton per square meter';
}