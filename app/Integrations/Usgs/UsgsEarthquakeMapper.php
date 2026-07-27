<?php

namespace App\Integrations\Usgs;

use App\Data\EarthquakeData;
use Carbon\CarbonImmutable;

class UsgsEarthquakeMapper
{
    public function map(array $feature): ?EarthquakeData
    {
        $id = data_get($feature, 'id');
        $magnitude = data_get($feature, 'properties.mag');
        $location = data_get($feature, 'properties.place');
        $time = data_get($feature, 'properties.time');
        $url = data_get($feature, 'properties.url');
        $coordinates = data_get($feature, 'geometry.coordinates');

        if (
            ! is_string($id)
            || ! is_numeric($magnitude)
            || ! is_string($location)
            || ! is_numeric($time)
            || ! is_string($url)
            || ! is_array($coordinates)
            || ! isset($coordinates[0], $coordinates[1], $coordinates[2])
            || ! is_numeric($coordinates[0])
            || ! is_numeric($coordinates[1])
            || ! is_numeric($coordinates[2])
        ) {
            return null;
        }

        return new EarthquakeData(
            id: $id,
            location: $location,
            magnitude: (float) $magnitude,
            occurredAt: CarbonImmutable::createFromTimestampMsUTC($time),
            longitude: (float) $coordinates[0],
            latitude: (float) $coordinates[1],
            depth: (float) $coordinates[2],
            url: $url,
        );
    }
}
