<?php

namespace PTV\Routing\DTO;

enum TollRoadType: string
{
    case GENERAL = "GENERAL";
    case CITY = "CITY";
    case BRIDGE = "BRIDGE";
    case TUNNEL = "TUNNEL";
    case FERRY = "FERRY";
    case MOUNTAIN_PASS = "MOUNTAIN_PASS";
}
