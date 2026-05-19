<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Imperial;

class HorsepowerHour extends Joule implements Imperial
{
  const FACTOR = 2684520;
  const SYMBOL = 'hp·h';
  const LABEL = 'horsepower-hour';
}