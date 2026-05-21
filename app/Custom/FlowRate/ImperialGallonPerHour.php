<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Imperial;

class ImperialGallonPerHour extends CubicMeterPerSecond implements Imperial
{
  const FACTOR = 0.00454609 / 3600; // 1 IGPH
  const SYMBOL = 'IGPH';
  const LABEL = 'imperial gallon per hour';
}