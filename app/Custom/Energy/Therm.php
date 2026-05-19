<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Imperial;

class Therm extends Joule implements Imperial
{
  const FACTOR = 105506000;
  const SYMBOL = 'thm';
  const LABEL = 'therm';
}