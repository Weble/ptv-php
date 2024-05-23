<?php

namespace PTV\Routing\DTO;

class TollSection
{
    public function __construct(
        /** @var array<TollSectionCost> */
        public readonly array  $costs,
        public readonly TollRoadType $tollRoadType,
        public readonly int $tollSystemIndex,
        public readonly string $countryCode,
        public readonly string $displayName,
        // meters
        public readonly int $calculatedDistance,
    )
    {
    }
}
