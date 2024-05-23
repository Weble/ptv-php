<?php

namespace PTV\Routing\DTO\Toll;

use Money\Money;
use PTV\Routing\Enums\EtcSubscriptionType;
use PTV\Routing\Enums\PaymentMethod;

class SectionCost
{
    public function __construct(

        public readonly Money $price,
        /** @var array<PaymentMethod> */
        public readonly array $paymentMethods,
        /** @var array<EtcSubscriptionType> */
        public readonly array $etcSubscriptions,
        public Money $convertedPrice,
    )
    {
    }
}
