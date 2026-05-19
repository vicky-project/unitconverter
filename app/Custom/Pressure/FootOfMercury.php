<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Imperial;

class FootOfMercury extends Pascal implements Imperial
{
  const FACTOR = 40636.7;
  const SYMBOL = 'ftHg';
  const LABEL = 'foot of mercury';
}