<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\USC;

class BarrelPerHour extends CubicMeterPerSecond implements USC
{
  const FACTOR = 0.158987 / 3600; // ≈ 4.41631e-5
  const SYMBOL = 'BPH';
  const LABEL = 'barrel per hour';
}