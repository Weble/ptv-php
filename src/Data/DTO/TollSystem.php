<?php

namespace PTV\Data\DTO;


class TollSystem
{
    public function __construct(

        public string $name,
        public string $operator,

        /** @var TariffVersion[] $tariffVersions */
        public array  $tariffVersions = [],
    )
    {
    }
}
