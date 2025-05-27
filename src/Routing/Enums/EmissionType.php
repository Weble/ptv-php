<?php

namespace PTV\Routing\Enums;

enum EmissionType: string
{
    case EN16258_2012 = 'en16258_2012';
    case EN16258_2012_HBEFA = 'en16258_2012_hbefa';
    case ISO14083_2022 = 'iso14083_2022';
    case ISO14083_2022_DEFAULT_CONSUMPTION = 'iso14083_2022_default_consumption';
    case ISO14083_2023 = 'iso14083_2023';
    case ISO14083_2023_DEFAULT_CONSUMPTION = 'iso14083_2023_default_consumption';
    case FRENCH_CO2E_DECREE_2017_639 = 'french_co2e_decree_2017_639';
}
