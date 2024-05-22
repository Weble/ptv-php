<?php

namespace PTV\Routing\DTO;

use DateInterval;

class Route
{
    public function __construct(
        public readonly Leg $leg,
        public readonly ?string  $routeId = null,
        /** @var array<Leg>|null */
        public readonly ?array  $legs = null,
        public readonly ?Toll $toll = null,
        public readonly ?array  $events = null,
        public readonly ?array $emissions = null,
        public readonly ?array  $alternativeRoutes = null,
        public readonly ?array $scheduleReport = null,
        public readonly ?array $evReport = null,
        public readonly ?string $guidedNavigation = null,
        public readonly ?MonetaryCosts $monetaryCosts = null,
        public readonly ?array  $warnings = null,
    )
    {
    }
}
