<?php

namespace App\Http\Controllers;

use App\Enums\EarthquakePeriod;
use App\Http\Requests\EarthquakeIndexRequest;
use App\Services\EarthquakeService;
use Inertia\Inertia;
use Inertia\Response;

class EarthquakeController extends Controller
{
    public function __construct(
        private readonly EarthquakeService $earthquakes,
    ) {}

    /**
     * Show the earthquake dashboard.
     */
    public function index(EarthquakeIndexRequest $request): Response
    {
        $search = $request->toSearchData();

        $dashboard = $this->earthquakes->dashboard($search);

        return Inertia::render('earthquakes/Index', [
            'earthquakes' => $dashboard->earthquakes,
            'summary' => [
                'total' => $dashboard->total,
                'strongestMagnitude' => $dashboard->strongestMagnitude,
                'averageMagnitude' => $dashboard->averageMagnitude,
            ],
            'filters' => [
                'period' => $request->validated('period', EarthquakePeriod::Day->value),
                'minimumMagnitude' => $search->minimumMagnitude,
            ],
        ]);
    }
}
