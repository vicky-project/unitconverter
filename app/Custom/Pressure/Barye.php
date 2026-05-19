<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\CGS;

class Barye extends Pascal implements CGS
{
  const FACTOR = 0.1;
  const SYMBOL = 'Ba';
  const LABEL = 'barye';
}