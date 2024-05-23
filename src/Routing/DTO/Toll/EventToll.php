<?php

namespace PTV\Routing\DTO\Toll;

use DateTimeImmutable;

class EventToll
{
    public function __construct(
        public readonly int $sectionIndex,
        public readonly string $displayName,
        public readonly string $accessType,
        public readonly int $relatedEventIndex,

    )
    {
    }
}
