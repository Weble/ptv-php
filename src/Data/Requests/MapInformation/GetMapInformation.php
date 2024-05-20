<?php

namespace PTV\Data\Requests\MapInformation;


use DateTimeImmutable;
use PTV\Data\DTO\Features;
use PTV\Data\DTO\MapInformation;
use PTV\Data\DTO\TariffVersion;
use PTV\Data\DTO\TollSystem;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use function Pest\version;

/**
 * getMapInformation
 *
 * Gets information about the map. See [here](./concepts/map-information) for more information.
 */
class GetMapInformation extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return "/map-information";
    }

    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json('geographicalUnits');

        return array_map(function (array $unit) {
            return new MapInformation(
                code: $unit['code'],
                country: $unit['country'],
                continent: $unit['continent'],
                features: new Features(
                    toll: $unit['features']['toll'],
                    tollSystems: array_map(fn(array $tollFeature) =>
                        new TollSystem(
                            name: $tollFeature['name'],
                            operator: $tollFeature['operator'],
                            tariffVersions: array_map(fn(array $version) =>
                            new TariffVersion(
                                version: $version['version'],
                                validFrom: DateTimeImmutable::createFromFormat('Y-m-d', $version['validFrom'])
                            ),$tollFeature['tariffVersions']),
                        )
                 , $unit['features']['tollFeatures']['tollSystems'] ?? []),
                ),
            );
        }, $data);
    }
}
