<?php

namespace PTV\Routing\DTO\Toll;

class Section
{
    public function __construct(
        /** @var array<SectionCost> */
        public readonly array    $costs,
        public readonly RoadType $tollRoadType,
        public readonly int      $tollSystemIndex,
        public readonly string   $countryCode,
        public readonly string   $displayName,
        // meters
        public readonly int      $calculatedDistance,
    )
    {
    }
}
