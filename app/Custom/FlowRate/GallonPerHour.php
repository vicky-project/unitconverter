<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\USC;

class GallonPerHour extends CubicMeterPerSecond implements USC
{
  const FACTOR = 0.00378541 / 3600; // 1 GPH
  const SYMBOL = 'GPH';
  const LABEL = 'gallon per hour';
}