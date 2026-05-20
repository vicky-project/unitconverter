<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Imperial;

class FootOfWater extends Pascal implements Imperial
{
  const FACTOR = 2989.07;
  const SYMBOL = 'ftH₂O';
  const LABEL = 'foot of water';
}