<?php

namespace PTV\Routing\DTO;

use Money\Money;

class CountryPrice
{
    public function __construct(
        public readonly string $code,
        public Money $price,
    )
    {
    }
}
