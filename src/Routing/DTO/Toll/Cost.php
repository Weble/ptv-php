<?php

namespace PTV\Routing\DTO\Toll;

use Money\Money;

class Cost
{
    public function __construct(
        /** @var array<Money> */
        public readonly array $prices,
        public Money $convertedPrice,
        /** @var array<CountryPrice> */
        public array $countries,
    )
    {
    }
}
