<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Imperial;

class Quad extends Joule implements Imperial
{
  const FACTOR = 1.05505585e18;
  const SYMBOL = 'quad';
  const LABEL = 'quad';
}