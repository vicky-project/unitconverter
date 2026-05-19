<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Imperial;

class KipPerSquareInch extends Pascal implements Imperial
{
  const FACTOR = 6894757;
  const SYMBOL = 'ksi';
  const LABEL = 'kip per square inch';
}