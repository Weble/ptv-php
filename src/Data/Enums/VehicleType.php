<?php

namespace PTV\Data\Enums;

enum VehicleType: string
{
    case TRUCK = 'TRUCK';
    case LCV = 'LCV';
    case SCV = 'SCV';

    case TRAILER = 'TRAILER';
    case SEMI_TRAILER = 'SEMI_TRAILER';
    case BODY = 'BODY';
}
