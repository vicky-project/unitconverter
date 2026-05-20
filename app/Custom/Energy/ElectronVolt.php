<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class ElectronVolt extends Joule implements Metric
{
  // CODATA 2018: 1 eV = 1.602176634e-19 J
  const FACTOR = 1.602176634e-19;
  const SYMBOL = 'eV';
  const LABEL = 'electronvolt';
}