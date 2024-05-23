<?php

namespace PTV\Routing\DTO;

use Money\Money;

class TollCost
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
