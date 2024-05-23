<?php

namespace PTV\Routing\Enums;

enum HazardousMaterial: string
{
    case HAZARDOUS_TO_WATER = "HAZARDOUS_TO_WATER";
    case EXPLOSIVE = "EXPLOSIVE";
    case FLAMMABLE = "FLAMMABLE";
    case RADIOACTIVE = "RADIOACTIVE";
    case INHALATION_HAZARD = "INHALATION_HAZARD";
    case MEDICAL_WASTE = "MEDICAL_WASTE";
    case OTHER = "OTHER";
    case NONE = "NONE";
}
