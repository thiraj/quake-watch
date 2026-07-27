<?php

namespace Tests\Unit\Services;

use App\Contracts\EarthquakeProvider;
use App\Data\EarthquakeData;
use App\Data\EarthquakeSearchData;
use App\Services\EarthquakeService;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

final class EarthquakeServiceTest extends TestCase
{
    public function test_it_builds_dashboard_statistics(): void
    {
        $provider = new class implements EarthquakeProvider
        {
            public function search(EarthquakeSearchData $search): array
            {
                return [
                    new EarthquakeData(
                        id: 'one',
                        location: 'Japan',
                        magnitude: 3.0,
                        occurredAt: CarbonImmutable::now('UTC'),
                        longitude: 140.0,
                        latitude: 36.0,
                        depth: 10.0,
                        url: 'https://example.com/one',
                    ),
                    new EarthquakeData(
                        id: 'two',
                        location: 'California',
                        magnitude: 5.0,
                        occurredAt: CarbonImmutable::now('UTC'),
                        longitude: -120.0,
                        latitude: 35.0,
                        depth: 15.0,
                        url: 'https://example.com/two',
                    ),
                ];
            }
        };

        $service = new EarthquakeService($provider);

        $endTime = CarbonImmutable::now('UTC');

        $search = new EarthquakeSearchData(
            startTime: $endTime->subDay(),
            endTime: $endTime,
            minimumMagnitude: 1.0,
            limit: 100,
        );

        $dashboard = $service->dashboard($search);

        $this->assertSame(2, $dashboard->total);
        $this->assertSame(5.0, $dashboard->strongestMagnitude);
        $this->assertSame(4.0, $dashboard->averageMagnitude);
    }
}
