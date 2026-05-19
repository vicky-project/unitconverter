<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class TonneOfCoalEquivalent extends Joule implements Metric
{
  const FACTOR = 2.93e10;
  const SYMBOL = 'tce';
  const LABEL = 'tonne of coal equivalent';
}