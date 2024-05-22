<?php

namespace PTV\Routing\DTO;

class Toll
{
    public function __construct(
        public readonly TollCosts $costs,
        public readonly Currencies $currencies,
    )
    {
    }
}
