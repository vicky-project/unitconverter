<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Imperial;

class PSI extends Pascal implements Imperial
{
  const FACTOR = 6894.757;
  const SYMBOL = 'psi';
  const LABEL = 'pound per square inch';
}