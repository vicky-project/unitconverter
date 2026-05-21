<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Imperial;

class AcreFootPerDay extends CubicMeterPerSecond implements Imperial
{
  // 1 acre-foot = 1233.48183754752 m³; 1 day = 86400 s
  const FACTOR = 1233.48183754752 / 86400; // ≈ 0.0142764
  const SYMBOL = 'AFD';
  const LABEL = 'acre-foot per day';
}