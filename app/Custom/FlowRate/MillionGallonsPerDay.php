<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\USC;

class MillionGallonsPerDay extends CubicMeterPerSecond implements USC
{
  // 1 MGD = 1,000,000 US gal/day = (0.00378541 * 1e6) / 86400 m³/s ≈ 0.0438126
  const FACTOR = (0.00378541 * 1000000) / 86400;
  const SYMBOL = 'MGD';
  const LABEL = 'million gallons per day';
}