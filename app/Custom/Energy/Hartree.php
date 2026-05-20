<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class Hartree extends Joule implements Metric
{
  const FACTOR = 4.35975e-18;
  const SYMBOL = 'E_h';
  const LABEL = 'hartree';
}