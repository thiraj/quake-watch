<?php

namespace App\Services;

use App\Contracts\EarthquakeProvider;
use App\Data\EarthquakeDashboardData;
use App\Data\EarthquakeData;
use App\Data\EarthquakeSearchData;

final readonly class EarthquakeService
{
    public function __construct(
        private EarthquakeProvider $provider,
    ) {}

    public function dashboard(
        EarthquakeSearchData $search,
    ): EarthquakeDashboardData {
        $earthquakes = $this->provider->search($search);

        if ($earthquakes === []) {
            return new EarthquakeDashboardData(
                earthquakes: [],
                total: 0,
                strongestMagnitude: null,
                averageMagnitude: null,
            );
        }

        $magnitudes = array_map(
            fn (EarthquakeData $earthquake): float => $earthquake->magnitude,
            $earthquakes,
        );

        return new EarthquakeDashboardData(
            earthquakes: $earthquakes,
            total: count($earthquakes),
            strongestMagnitude: max($magnitudes),
            averageMagnitude: round(
                array_sum($magnitudes) / count($magnitudes),
                2,
            ),
        );
    }
}
