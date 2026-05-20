<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class DegreePerSecond extends Hertz implements Metric
{
  // 1 Hz = 360 °/s  ⇒  1 °/s = 1/360 Hz
  const FACTOR = 1 / 360;
  const SYMBOL = '°/s';
  const LABEL = 'degree per second';
}