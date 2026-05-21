<?php

namespace Modules\UnitConverter\Custom\FlowRate;

use PhpUnitConversion\System\Metric;
use PhpUnitConversion\Traits\BaseUnit;
use Modules\UnitConverter\Custom\FlowRate as BaseFlowRate;

class CubicMeterPerSecond extends BaseFlowRate implements Metric
{
  use BaseUnit;

  const SYMBOL = 'm³/s';
  const LABEL = 'cubic meter per second';
}