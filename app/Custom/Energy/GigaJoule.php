<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class GigaJoule extends Joule implements Metric
{
  const FACTOR = 1000000000;
  const SYMBOL = 'GJ';
  const LABEL = 'gigajoule';
}