<?php

namespace PTV\Routing;

use PTV\PTV;
use PTV\Routing\Resource\ReachableAreas;
use PTV\Routing\Resource\ReachableLocations;
use PTV\Routing\Resource\Routing;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\HeaderAuthenticator;

/**
 * Routing
 *
 * With the Routing service you can calculate routes from A to B taking into account vehicle-specific restrictions, traffic situations, toll, emissions, driver's working hours, service times and opening intervals.
 */
class PTVRouting extends PTV
{
    public function resolveBaseUrl(): string
    {
        return parent::resolveBaseUrl() . 'routing/v1';
    }

    public function reachableAreas(): ReachableAreas
    {
        return new ReachableAreas($this);
    }


    public function reachableLocations(): ReachableLocations
    {
        return new ReachableLocations($this);
    }


    public function route(): Routing
    {
        return new Routing($this);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'language' => $this->language
        ]);
    }

    protected function defaultAuth(): ?Authenticator
    {
        return new HeaderAuthenticator(
            accessToken: $this->apiKey,
            headerName: 'apiKey'
        );
    }
}
