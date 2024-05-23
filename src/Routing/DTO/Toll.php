<?php

namespace PTV\Routing\DTO;

use PTV\Data\DTO\TollSystem;

class Toll
{
    public function __construct(
        public readonly ?TollCost   $costs = null,
        public readonly ?Currencies $currencies = null,
        /** @var array<TollSystem>|null */
        public readonly ?array      $systems = null,
        /** @var array<TollSection>|null */
        public readonly ?array      $sections = null,
    )
    {
    }
}
