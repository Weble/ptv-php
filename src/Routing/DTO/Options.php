<?php

namespace PTV\Routing\DTO;

use DateTimeImmutable;
use DateTimeInterface;
use Money\Currency;
use PTV\Data\Enums\PolylineFormat;
use PTV\Routing\Enums\Feature;
use PTV\Routing\Enums\RoutingMode;
use PTV\Routing\Enums\TrafficMode;

class Options
{
    public function __construct(
        public readonly ?DateTimeInterface $startTime = null,
        public readonly ?DateTimeInterface $arrivalTime = null,
        public readonly ?DateTimeInterface $tollTime = null,
        public readonly ?TrafficMode $trafficMode = null,
        public readonly ?string $language = null,
        public readonly ?PolylineFormat $polylineFormat = null,
        public readonly ?string $allowedCountries = null,
        public readonly ?string $prohibitedCountries = null,
        public readonly ?Currency $currency = null,
        public readonly ?bool $preferTurnsOnPassengerSide = null,
        /** @var array<Feature>|null */
        public readonly ?array $avoid = null,
        /** @var array<string>null */
        public readonly ?array $blockIntersectingRoads = null,
        /** @var array<string>|null */
        public readonly ?array $customRoadAttributeScenarios = null,
        public readonly ?RoutingMode $routingMode = null,
    )
    {
    }
}
