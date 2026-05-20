<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Imperial;

class BritishThermalUnit extends Joule implements Imperial
{
  const FACTOR = 1055.06;
  const SYMBOL = 'BTU';
  const LABEL = 'British thermal unit';
}