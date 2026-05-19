<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class MegaJoule extends Joule implements Metric
{
  const FACTOR = 1000000;
  const SYMBOL = 'MJ';
  const LABEL = 'megajoule';
}