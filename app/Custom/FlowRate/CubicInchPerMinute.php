<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Imperial;

class CubicInchPerMinute extends CubicMeterPerSecond implements Imperial
{
  const FACTOR = 1.6387064e-5 / 60; // ≈ 2.73117733e-7
  const SYMBOL = 'in³/min';
  const LABEL = 'cubic inch per minute';
}