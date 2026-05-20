<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class RadianPerSecond extends Hertz implements Metric
{
  // 1 Hz = 2π rad/s → 1 rad/s = 1/(2π) Hz
  const FACTOR = 1 / (2 * M_PI);
  const SYMBOL = 'rad/s';
  const LABEL = 'radian per second';
}