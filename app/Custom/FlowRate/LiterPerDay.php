<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;

class LiterPerDay extends CubicMeterPerSecond implements Metric
{
  const FACTOR = 0.001 / 86400; // ≈ 1.15741e-8
  const SYMBOL = 'L/day';
  const LABEL = 'liter per day';
}