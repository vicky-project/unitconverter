<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Imperial;

class InchOfWater extends Pascal implements Imperial
{
  const FACTOR = 249.0889;
  const SYMBOL = 'inH₂O';
  const LABEL = 'inch of water';
}