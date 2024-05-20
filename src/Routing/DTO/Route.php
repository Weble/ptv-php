<?php

namespace PTV\Routing\DTO;

use DateInterval;

class Route
{
    public function __construct(
        // Meters
        public int     $distance,
        // minutes
        public int     $travelTime,
        public int     $trafficDelay,
        public bool    $violated,
        public ?string  $routeId = null,
        public ?array  $legs = null,
        public ?object $toll = null,
        public ?string $polyline = null,
        public ?array  $events = null,
        public ?object $emissions = null,
        public ?array  $alternativeRoutes = null,
        public ?object $scheduleReport = null,
        public ?object $evReport = null,
        public ?string $guidedNavigation = null,
        public ?object $monetaryCosts = null,
        public ?array  $warnings = null,
    )
    {
    }

    public function travelTime(): DateInterval
    {
        return new DateInterval("PT{$this->travelTime}S");
    }
}
