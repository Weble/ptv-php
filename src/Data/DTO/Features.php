<?php

namespace PTV\Data\DTO;


class Features
{
    public function __construct(

        public bool         $toll,
        /** @var  TollSystem[] $tollSystems */
        public array $tollSystems = []
    )
    {
    }
}
