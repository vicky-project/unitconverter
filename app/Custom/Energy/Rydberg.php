<?php

namespace Modules\UnitConverter\Custom\Energy;

use PhpUnitConversion\System\Metric;

class Rydberg extends Joule implements Metric
{
  const FACTOR = 2.17987e-18;
  const SYMBOL = 'Ry';
  const LABEL = 'rydberg';
}