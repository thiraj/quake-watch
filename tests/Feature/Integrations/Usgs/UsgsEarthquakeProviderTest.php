<?php

namespace Tests\Feature\Integrations\Usgs;

use App\Contracts\EarthquakeProvider;
use App\Data\EarthquakeSearchData;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class UsgsEarthquakeProviderTest extends TestCase
{
    public function test_it_fetches_and_maps_earthquakes(): void
    {
        Cache::clear();

        Http::fake([
            'earthquake.usgs.gov/*' => Http::response([
                'features' => [
                    [
                        'id' => 'us123',
                        'properties' => [
                            'mag' => 4.8,
                            'place' => 'California',
                            'time' => 1785100000000,
                            'url' => 'https://earthquake.usgs.gov/example',
                        ],
                        'geometry' => [
                            'coordinates' => [
                                -120.4,
                                35.2,
                                10.5,
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $endTime = CarbonImmutable::parse(
            '2026-07-27 12:00:00',
            'UTC',
        );

        $search = new EarthquakeSearchData(
            startTime: $endTime->subDay(),
            endTime: $endTime,
            minimumMagnitude: 3.0,
            limit: 10,
        );

        $provider = app(EarthquakeProvider::class);

        $earthquakes = $provider->search($search);

        $this->assertCount(1, $earthquakes);
        $this->assertSame('us123', $earthquakes[0]->id);
        $this->assertSame('California', $earthquakes[0]->location);
        $this->assertSame(4.8, $earthquakes[0]->magnitude);
        $this->assertSame(-120.4, $earthquakes[0]->longitude);
        $this->assertSame(35.2, $earthquakes[0]->latitude);
        $this->assertSame(10.5, $earthquakes[0]->depth);
    }
}
