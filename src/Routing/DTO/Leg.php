<?php

namespace PTV\Routing\DTO;

use DateInterval;

class Leg
{
    public function __construct(
        // Meters
        public readonly int     $distance,
        // minutes
        public readonly int     $travelTime,
        public readonly int     $trafficDelay,
        public readonly bool    $violated,
        public readonly ?string $polyline = null,
    )
    {

    }

    public function travelTime(): DateInterval
    {
        return new DateInterval("PT{$this->travelTime}S");
    }
}
