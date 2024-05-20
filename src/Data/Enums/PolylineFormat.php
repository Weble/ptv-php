<?php

namespace PTV\Data\Enums;

enum PolylineFormat: string
{
    case GEO_JSON = "GEO_JSON";
    case GOOGLE_ENCODED_POLYLINE = "GOOGLE_ENCODED_POLYLINE";
}
