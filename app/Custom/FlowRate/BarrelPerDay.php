<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\USC;

class BarrelPerDay extends CubicMeterPerSecond implements USC
{
  const FACTOR = 0.158987 / 86400; // 1 BPD ≈ 1.84×10⁻⁶ m³/s
  const SYMBOL = 'BPD';
  const LABEL = 'barrel per day';
}