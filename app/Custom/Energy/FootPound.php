<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Imperial;

class FootPound extends Joule implements Imperial
{
  const FACTOR = 1.35582;
  const SYMBOL = 'ft·lb';
  const LABEL = 'foot-pound';
}