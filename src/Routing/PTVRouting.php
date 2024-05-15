<?php

namespace PTV\Routing;

use Psr\Http\Message\RequestInterface;
use PTV\PTV;
use PTV\Routing\Resource\ReachableAreas;
use PTV\Routing\Resource\ReachableLocations;
use PTV\Routing\Resource\Routing;
use Saloon\Contracts\Authenticator;
use Saloon\Helpers\URLHelper;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\PendingRequest;

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

    public function handlePsrRequest(RequestInterface $request, PendingRequest $pendingRequest): RequestInterface
    {
        $existingQuery = URLHelper::parseQueryString($request->getUri()->getQuery());

        // This is because APIs query params needs to be like 'param=value1&param=value2' instead of 'param[0]=value1&param[1]=value2'
        return $request->withUri(
            $request->getUri()->withQuery(
                preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', http_build_query($existingQuery))
            )
        );
    }
}
