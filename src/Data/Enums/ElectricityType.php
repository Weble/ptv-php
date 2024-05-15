<?php

namespace PTV\Data\Enums;

enum ElectricityType: string
{
    case BATTERY = "BATTERY";
    case HYDROGEN_FUEL_CELL = "HYDROGEN_FUEL_CELL";
    case NONE = "NONE";
}
