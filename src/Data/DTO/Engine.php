<?php

namespace PTV\Data\DTO;


class Engine
{
    public function __construct(

        public string $engineType,
        public int    $maximumSpeed,
        public int    $ecoSpeed,
        public string $emissionStandard,
        public ?int    $power = null,
        public ?int    $officialRange = null,
    )
    {
    }
}
