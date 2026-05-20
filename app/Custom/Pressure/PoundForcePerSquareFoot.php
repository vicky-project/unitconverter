<?php

namespace Modules\UnitConverter\Custom\Pressure;

use PhpUnitConversion\System\Imperial;

class PoundForcePerSquareFoot extends Pascal implements Imperial
{
  const FACTOR = 47.8803;
  const SYMBOL = 'psf';
  const LABEL = 'pound-force per square foot';
}