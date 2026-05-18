<?php
return [
  'name' => 'UnitConverter',
  'units' => [
    // Angle
    Mesura\Angle\Degree::class,
    Mesura\Angle\Radian::class,

    // Area
    Mesura\Area\Acre::class,
    Mesura\Area\Hectare::class,
    Mesura\Area\SquareFoot::class,
    Mesura\Area\SquareKilometer::class,
    Mesura\Area\SquareMeter::class,
    Mesura\Area\SquareMile::class,

    // ArealDensity
    Mesura\ArealDensity\GramPerSquareMeter::class,
    Mesura\ArealDensity\KilogramPerSquareMeter::class,
    Mesura\ArealDensity\OuncePerSquareYard::class,
    Mesura\ArealDensity\PoundPerSquareFoot::class,

    // Energy
    Mesura\Energy\BritishThermalUnit::class,
    Mesura\Energy\Calorie::class,
    Mesura\Energy\FootPound::class,
    Mesura\Energy\Joule::class,
    Mesura\Energy\Kilocalorie::class,
    Mesura\Energy\KilowattHour::class,
    Mesura\Energy\WattHour::class,

    // Irradiance
    Mesura\Irradiance\BtuPerHourPerSquareFoot::class,
    Mesura\Irradiance\KilowattPerSquareMeter::class,
    Mesura\Irradiance\WattPerSquareMeter::class,

    // Length
    Mesura\Length\Centimeter::class,
    Mesura\Length\Fathom::class,
    Mesura\Length\Foot::class,
    Mesura\Length\Furlong::class,
    Mesura\Length\HorseLength::class,
    Mesura\Length\Inch::class,
    Mesura\Length\Kilometer::class,
    Mesura\Length\Meter::class,
    Mesura\Length\Millimeter::class,
    Mesura\Length\NauticalMile::class,
    Mesura\Length\StatuteMile::class,
    Mesura\Length\SurveyMile::class,
    Mesura\Length\Thou::class,
    Mesura\Length\Yard::class,
    Mesura\Length\Quettameter::class,
    Mesura\Length\Quectometer::class,
    Mesura\Length\Rontometer::class,
    Mesura\Length\Yottameter::class,
    Mesura\Length\Zettameter::class,
    Mesura\Length\Exameter::class,
    Mesura\Length\Petameter::class,
    Mesura\Length\Terameter::class,
    Mesura\Length\Gigameter::class,
    Mesura\Length\Megameter::class,
    Mesura\Length\Decameter::class,
    Mesura\Length\Hectometer::class,
    Mesura\Length\Picometer::class,
    Mesura\Length\Femtometer::class,
    Mesura\Length\Attometer::class,
    Mesura\Length\Zeptometer::class,
    Mesura\Length\Yoctometer::class,

    // MassConcentration
    Mesura\MassConcentration\GrainPerCubicFoot::class,
    Mesura\MassConcentration\GrainPerCubicMeter::class,
    Mesura\MassConcentration\GramPerCubicMeter::class,
    Mesura\MassConcentration\KilogramPerCubicMeter::class,
    Mesura\MassConcentration\MicrogramPerCubicMeter::class,
    Mesura\MassConcentration\MilligramPerCubicMeter::class,

    // Percentage
    Mesura\Percentage\Percent::class,
    Mesura\Percentage\PartsPerMillion::class,
    Mesura\Percentage\PartsPerBillion::class,

    // Power
    Mesura\Power\BtuPerHour::class,
    Mesura\Power\CaloriePerSecond::class,
    Mesura\Power\FootPoundPerSecond::class,
    Mesura\Power\Horsepower::class,
    Mesura\Power\Watt::class,
    Mesura\Power\Kilowatt::class,
    Mesura\Power\Megawatt::class,
    Mesura\Power\Gigawatt::class,

    // Pressure
    Mesura\Pressure\Bar::class,
    Mesura\Pressure\Hectopascal::class,
    Mesura\Pressure\Kilopascal::class,
    Mesura\Pressure\Millibar::class,
    Mesura\Pressure\MillimetreOfMercury::class,
    Mesura\Pressure\Pascal::class,
    Mesura\Pressure\PoundPerSquareInch::class,
    Mesura\Pressure\StandardAtmosphere::class,
    Mesura\Pressure\Torr::class,

    // SpecificEnergy
    Mesura\SpecificEnergy\BtuPerPound::class,
    Mesura\SpecificEnergy\CaloriePerGram::class,
    Mesura\SpecificEnergy\JoulePerKilogram::class,
    Mesura\SpecificEnergy\KilojoulePerKilogram::class,
    Mesura\SpecificEnergy\MegajoulePerKilogram::class,

    // Speed
    Mesura\Speed\KilometerPerHour::class,
    Mesura\Speed\Knot::class,
    Mesura\Speed\MeterPerSecond::class,
    Mesura\Speed\MilesPerHour::class,

    // Temperature
    Mesura\Temperature\Celsius::class,
    Mesura\Temperature\Fahrenheit::class,
    Mesura\Temperature\Kelvin::class,
    Mesura\Temperature\Rankine::class,

    // Time
    Mesura\Time\Day::class,
    Mesura\Time\Hour::class,
    Mesura\Time\Minute::class,
    Mesura\Time\Second::class,

    // Torque
    Mesura\Torque\NewtonMeter::class,
    Mesura\Torque\PoundForceFoot::class,

    // Volume
    Mesura\Volume\CubicInch::class,
    Mesura\Volume\CubicMeter::class,
    Mesura\Volume\CubicYard::class,
    Mesura\Volume\FluidDram::class,
    Mesura\Volume\FluidOunce::class,
    Mesura\Volume\ImperialFluidDram::class,
    Mesura\Volume\ImperialFluidOunce::class,
    Mesura\Volume\ImperialPint::class,
    Mesura\Volume\ImperialQuart::class,
    Mesura\Volume\Liter::class,
    Mesura\Volume\Pint::class,
    Mesura\Volume\Quart::class,
    Mesura\Volume\TableSpoon::class,
    Mesura\Volume\Milliliter::class,
    Mesura\Volume\Centiliter::class,
    Mesura\Volume\Deciliter::class,
    Mesura\Volume\Hectoliter::class,
    Mesura\Volume\Kiloliter::class,

    // Weight
    Mesura\Weight\Gram::class,
    Mesura\Weight\Kilogram::class,
    Mesura\Weight\MetricTon::class,
    Mesura\Weight\Pound::class,
    Mesura\Weight\Ounce::class,
    Mesura\Weight\Stone::class,
    Mesura\Weight\Microgram::class,
    Mesura\Weight\Milligram::class,
    Mesura\Weight\Nanogram::class,
    Mesura\Weight\Picogram::class,
    Mesura\Weight\Femtogram::class,
    Mesura\Weight\Attogram::class,
    Mesura\Weight\Zeptogram::class,
    Mesura\Weight\Yoctogram::class,
    Mesura\Weight\Decagram::class,
    Mesura\Weight\Hectogram::class,
    Mesura\Weight\Megagram::class,
    Mesura\Weight\Gigagram::class,
    Mesura\Weight\Teragram::class,
    Mesura\Weight\Petagram::class,
    Mesura\Weight\Exagram::class,
    Mesura\Weight\Zettagram::class,
    Mesura\Weight\Yottagram::class,
    Mesura\Weight\Quettagram::class,
    Mesura\Weight\Rontogram::class,
    Mesura\Weight\Quectogram::class,
  ],
];