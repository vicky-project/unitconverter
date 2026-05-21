<?php
namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\USC;

class GallonPerDay extends CubicMeterPerSecond implements USC
{
  const FACTOR = 0.00378541 / 86400; // ≈ 4.38126e-8
  const SYMBOL = 'GPD';
  const LABEL = 'gallon per day';
}