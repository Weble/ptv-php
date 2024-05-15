<?php

namespace PTV;

use Psr\Http\Message\RequestInterface;
use PTV\Data\PTVData;
use PTV\Routing\PTVRouting;
use Saloon\Contracts\Authenticator;
use Saloon\Helpers\URLHelper;
use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\PendingRequest;

/**
 * Routing
 *
 * With the Routing service you can calculate routes from A to B taking into account vehicle-specific restrictions, traffic situations, toll, emissions, driver's working hours, service times and opening intervals.
 */
class PTV extends Connector
{
    public function __construct(
        protected readonly string $apiKey,
        protected readonly ?string $language = null
    )
    {
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.myptv.com/';
    }

    public function routing(): PTVRouting
    {
        return new PTVRouting($this->apiKey, $this->language);
    }

    public function data(): PTVData
    {
        return new PTVData($this->apiKey, $this->language);
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
