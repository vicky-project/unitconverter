<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Imperial;

class InchPound extends Joule implements Imperial
{
  const FACTOR = 0.11298483;
  const SYMBOL = 'in·lb';
  const LABEL = 'inch-pound';
}