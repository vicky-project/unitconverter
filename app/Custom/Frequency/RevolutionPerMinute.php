<?php

namespace Modules\UnitConverter\Custom\Frequency;

use PhpUnitConversion\System\Metric;

class RevolutionPerMinute extends Hertz implements Metric
{
  const FACTOR = 1 / 60; // 1 RPM = 1/60 Hz ≈ 0.0166667
  const SYMBOL = 'rpm';
  const LABEL = 'revolution per minute';
}