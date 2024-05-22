<?php

namespace PTV\Routing\DTO;

use DateTimeImmutable;
use Money\Currency;

class Currencies
{
    public function __construct(
        public readonly DateTimeImmutable $date,
        public readonly string $provider,
        public readonly Currency $baseCurrency,
        /** @var array<ExchangeRate> */
        public readonly array $exchangeRates,
    )
    {
    }
}
