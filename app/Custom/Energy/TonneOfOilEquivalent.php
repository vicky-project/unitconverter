<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class TonneOfOilEquivalent extends Joule implements Metric
{
  const FACTOR = 4.1868e10;
  const SYMBOL = 'toe';
  const LABEL = 'tonne of oil equivalent';
}