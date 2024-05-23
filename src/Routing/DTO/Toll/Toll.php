<?php

namespace PTV\Routing\DTO\Toll;

use PTV\Data\DTO\TollSystem;
use PTV\Routing\DTO\Currencies;

class Toll
{
    public function __construct(
        public readonly ?Cost       $costs = null,
        public readonly ?Currencies $currencies = null,
        /** @var array<TollSystem>|null */
        public readonly ?array      $systems = null,
        /** @var array<Section>|null */
        public readonly ?array      $sections = null,
        /** @var array<Event>|null */
        public readonly ?array      $events = null,
    )
    {
    }
}
