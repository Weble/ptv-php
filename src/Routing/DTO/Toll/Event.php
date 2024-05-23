<?php

namespace PTV\Routing\DTO\Toll;

use DateTimeImmutable;

class Event
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly DateTimeImmutable $startsAt,
        public readonly int $distanceFromStart,
        public readonly int $travelTimeFromStart,
        public readonly string $countryCode,
        public readonly int $utcOffset,
        public readonly EventToll $toll,
    )
    {
    }
}
