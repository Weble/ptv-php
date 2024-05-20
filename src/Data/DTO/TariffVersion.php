<?php

namespace PTV\Data\DTO;


use DateTimeImmutable;

class TariffVersion
{
    public function __construct(

        public string $version,
        public DateTimeImmutable $validFrom,
    )
    {
    }
}
