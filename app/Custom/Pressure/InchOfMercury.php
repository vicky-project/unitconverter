<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Imperial;

class InchOfMercury extends Pascal implements Imperial
{
  const FACTOR = 3386.39;
  const SYMBOL = 'inHg';
  const LABEL = 'inch of mercury';
}