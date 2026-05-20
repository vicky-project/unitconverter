<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Metric;

class Torr extends Pascal implements Metric
{
  const FACTOR = 133.322;
  const SYMBOL = 'Torr';
  const LABEL = 'torr';
}