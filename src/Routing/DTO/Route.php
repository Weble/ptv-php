<?php

namespace PTV\Routing\DTO;

use PTV\Routing\DTO\Toll\Event;
use PTV\Routing\DTO\Toll\Toll;

class Route
{
    public function __construct(
        public readonly Leg            $leg,
        public readonly ?string        $routeId = null,
        /** @var array<Leg>|null */
        public readonly ?array         $legs = null,
        public readonly ?Toll          $toll = null,
        /** @var array<Event>|null */
        public readonly ?array         $events = null,
        public readonly ?array         $emissions = null,
        /** @var array<Route>|null */
        public readonly ?array         $alternativeRoutes = null,
        public readonly ?array         $scheduleReport = null,
        public readonly ?array         $evReport = null,
        public readonly ?string        $guidedNavigation = null,
        public readonly ?MonetaryCosts $monetaryCosts = null,
        public readonly ?array         $warnings = null,
        public readonly ?array         $errors = null,
    )
    {
    }

    /**
     * Returns the contents in binary format of the .brt file for the guided navigation systems.
     * @return string|null
     */
    public function guidedNavigationBinary(): ?string
    {
        if ($this->guidedNavigation === null) {
            return null;
        }

        return base64_decode($this->guidedNavigation);
    }
}
