<?php

namespace App\Data;

final readonly class EarthquakeDashboardData
{
    /**
     * @param  list<EarthquakeData>  $earthquakes
     */
    public function __construct(
        public array $earthquakes,
        public int $total,
        public ?float $strongestMagnitude,
        public ?float $averageMagnitude,
    ) {}
}
