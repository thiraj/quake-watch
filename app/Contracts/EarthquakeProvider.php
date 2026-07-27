<?php

namespace App\Contracts;

use App\Data\EarthquakeData;
use App\Data\EarthquakeSearchData;

interface EarthquakeProvider
{
    /**
     * @return list<EarthquakeData>
     */
    public function search(EarthquakeSearchData $search): array;
}
