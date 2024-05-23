<?php

namespace PTV\Routing\Requests\Routing;

use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PTV\Data\DTO\TariffVersion;
use PTV\Data\DTO\TollSystem;
use PTV\Routing\DTO\Currencies;
use PTV\Routing\DTO\ExchangeRate;
use PTV\Routing\DTO\Leg;
use PTV\Routing\DTO\MonetaryCosts;
use PTV\Routing\DTO\Route;
use PTV\Routing\DTO\Toll\Cost;
use PTV\Routing\DTO\Toll\CountryPrice;
use PTV\Routing\DTO\Toll\Event;
use PTV\Routing\DTO\Toll\EventToll;
use PTV\Routing\DTO\Toll\RoadType;
use PTV\Routing\DTO\Toll\Section;
use PTV\Routing\DTO\Toll\SectionCost;
use PTV\Routing\DTO\Toll\Toll;
use PTV\Routing\Enums\EtcSubscriptionType;
use PTV\Routing\Enums\PaymentMethod;

trait CreateRouteFromResponse
{
    private function parseRoute(array $data): Route
    {
        return new Route(
            leg: $this->parseLeg($data),
            routeId: $data['routeId'] ?? null,
            legs: isset($data['legs']) ? array_map(fn(array $leg): Leg => $this->parseLeg($leg), $data['legs']) : null,
            toll: isset($data['toll']) ? $this->parseToll($data) : null,
            events: $data['events'] ?? null,
            emissions: $data['emissions'] ?? null,
            alternativeRoutes: isset($data['alternativeRoutes']) ? array_map(fn(array $route): Route => $this->parseRoute($route), $data['alternativeRoutes']) : null,
            scheduleReport: $data['scheduleReport'] ?? null,
            evReport: $data['evReport'] ?? null,
            guidedNavigation: $data['guidedNavigation'] ?? null,
            monetaryCosts: isset($data['monetaryCosts']) ? $this->parseCosts($data['monetaryCosts']) : null,
        );
    }

    private function parseLeg(array $data): Leg
    {
        return new Leg(
            distance: $data['distance'] ?? null,
            travelTime: $data['travelTime'] ?? null,
            trafficDelay: $data['trafficDelay'] ?? null,
            violated: $data['violated'] ?? null,
            polyline: $data['polyline'] ?? null,
        );
    }

    private function parseCosts(array $monetaryCosts): MonetaryCosts
    {
        $currency = new Currency($monetaryCosts['currency']);
        return new MonetaryCosts(
            totalCost: $this->parseMoney($monetaryCosts['totalCost'], $currency),
            distanceCost: $this->parseMoney($monetaryCosts['distanceCost'], $currency),
            workingTimeCost: $this->parseMoney($monetaryCosts['workingTimeCost'], $currency),
            energyCost: $this->parseMoney($monetaryCosts['energyCost'], $currency),
            tollCost: $this->parseMoney($monetaryCosts['tollCost'], $currency),
        );
    }

    private function parseMoney(float $value, Currency $currency): Money
    {
        $value = intval($value * 100);
        return new Money($value, $currency);
    }

    private function parseToll(array $data): Toll
    {
        return new Toll(
            costs: isset($data['toll']['costs']) ? $this->parseTollCosts($data['toll']['costs']) : null,
            currencies: isset($data['toll']['currencies']) ? $this->parseTollCurrencies($data['toll']['currencies']) : null,
            systems: isset($data['toll']['systems']) ? array_map(fn(array $system): TollSystem => $this->parseTollSystem($system), $data['toll']['systems']) : null,
            sections: isset($data['toll']['sections']) ? array_map(fn(array $section): Section => $this->parseTollSection($section), $data['toll']['sections']) : null,
            events: isset($data['toll']['events']) ? array_map(fn(array $event): Event => $this->parseTollEvent($event), $data['toll']['events']) : null,
        );
    }

    private function parseTollEvent(array $event): Event
    {
        return new Event(
            latitude: $event['latitude'],
            longitude: $event['longitude'],
            startsAt: DateTimeImmutable::createFromFormat(DATE_ATOM, $event['startsAt']),
            distanceFromStart: $event['distanceFromStart'],
            travelTimeFromStart: $event['travelTimeFromStart'],
            countryCode: $event['countryCode'],
            utcOffset: $event['utcOffset'],
            toll: new EventToll(
                sectionIndex: $event['toll']['sectionIndex'],
                displayName: $event['toll']['displayName'],
                accessType: $event['toll']['accessType'],
                relatedEventIndex: $event['toll']['relatedEventIndex'],
            ),
        );
    }

    private function parseTollSection(array $section): Section
    {
        return new Section(
            costs: array_map(fn(array $cost): SectionCost => new SectionCost(
                price: $this->parseMoney($cost['price'], new Currency($cost['price'])),
                paymentMethods: array_map(fn(string $method): PaymentMethod => PaymentMethod::from($method), $cost['paymentMethods']),
                etcSubscriptions: array_map(fn(string $sub): EtcSubscriptionType => EtcSubscriptionType::from($sub), $cost['etcSubscriptions']),
                convertedPrice: $this->parseMoney($cost['convertedPrice']['price'], new Currency($cost['convertedPrice']['currency'])),
            ), $section['costs']),
            tollRoadType: RoadType::from($section['tollRoadType']),
            tollSystemIndex: $section['tollSystemIndex'],
            countryCode: $section['countryCode'],
            displayName: $section['displayName'],
            calculatedDistance: $section['calculatedDistance']

        );
    }

    private function parseTollSystem(array $system): TollSystem
    {
        return new TollSystem(
            name: $system['name'],
            operator: $system['operatorName'],
            tariffVersions: [
                new TariffVersion(
                    version: $system['tariffVersion'],
                    validFrom: DateTimeImmutable::createFromFormat("Y-m-d\TH:i:s.v\Z", $system['tariffVersionValidFrom'])
                )
            ]
        );
    }

    private function parseTollCurrencies(array $currencies): Currencies
    {
        return new Currencies(
            date: DateTimeImmutable::createFromFormat('Y-m-d', $currencies['date']),
            provider: $currencies['provider'],
            baseCurrency: new Currency($currencies['baseCurrency']),
            exchangeRates: array_map(
                fn(array $rate): ExchangeRate => new ExchangeRate(
                    currency: new Currency($rate['currency']),
                    rate: $rate['rate'],
                ),
                $currencies['exchangeRates'])
        );
    }

    private function parseTollCosts(array $costs): Cost
    {
        return new Cost(
            prices: array_map(
                fn(array $price): Money => $this->parseMoney($price['price'], new Currency($price['currency'])),
                $costs['prices']
            ),
            convertedPrice: $this->parseMoney($costs['convertedPrice']['price'], new Currency($costs['convertedPrice']['currency'])),
            countries: array_map(
                fn(array $country): CountryPrice => new CountryPrice(
                    code: $country['countryCode'],
                    price: $this->parseMoney($country['price']['price'], new Currency($country['price']['currency']))
                ),
                $costs['countries']
            )
        );
    }

}
