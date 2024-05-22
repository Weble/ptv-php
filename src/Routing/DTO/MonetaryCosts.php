<?php

namespace PTV\Routing\DTO;

use Money\Money;

class MonetaryCosts
{
    public function __construct(
        public readonly Money  $totalCost,
        public readonly Money  $distanceCost,
        public readonly Money  $workingTimeCost,
        public readonly Money  $energyCost,
        public readonly Money  $tollCost,
    )
    {
    }
}
