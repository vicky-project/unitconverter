<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Imperial;

class CubicInchPerSecond extends CubicMeterPerSecond implements Imperial
{
  const FACTOR = 1.6387064e-5; // 1 in³ = 1.6387064e-5 m³
  const SYMBOL = 'in³/s';
  const LABEL = 'cubic inch per second';
}