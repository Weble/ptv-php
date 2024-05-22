<?php

namespace PTV\Routing\DTO;

use Money\Currency;

class ExchangeRate
{
    public function __construct(
        public readonly Currency $currency,
        public readonly float $rate,
    )
    {
    }
}
