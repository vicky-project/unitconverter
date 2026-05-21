<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Imperial;

class StandardCubicFeetPerMinute extends CubicMeterPerSecond implements Imperial
{
  // 1 SCFM = 0.0004719474432 m³/s (standard conditions)
  const FACTOR = 0.0004719474432;
  const SYMBOL = 'SCFM';
  const LABEL = 'standard cubic foot per minute';
}