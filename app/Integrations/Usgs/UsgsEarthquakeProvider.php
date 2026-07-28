<?php

namespace App\Integrations\Usgs;

use App\Contracts\EarthquakeProvider;
use App\Data\EarthquakeData;
use App\Data\EarthquakeSearchData;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Client\Factory as HttpFactory;
use UnexpectedValueException;

final class UsgsEarthquakeProvider implements EarthquakeProvider
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly UsgsEarthquakeMapper $mapper,
        private readonly CacheRepository $cache,
    ) {}

    /**
     * @return list<EarthquakeData>
     */
    public function search(EarthquakeSearchData $search): array
    {
        $features = $this->cache->remember(
            $this->cacheKey($search),
            now()->addMinutes(
                config('earthquakes.providers.usgs.cache_ttl', 5)
            ),
            fn (): array => $this->fetch($search),
        );

        return $this->mapFeatures($features);
    }

    private function fetch(EarthquakeSearchData $search): array
    {
        $response = $this->http
            ->baseUrl(config('earthquakes.providers.usgs.base_url'))
            ->acceptJson()
            ->connectTimeout(2)
            ->timeout(5)
            ->get('/fdsnws/event/1/query', [
                'format' => 'geojson',
                'starttime' => $search->startTime->toIso8601String(),
                'endtime' => $search->endTime->toIso8601String(),
                'minmagnitude' => $search->minimumMagnitude,
                'limit' => $search->limit,
                'orderby' => 'time',
            ]);

        $response->throw();

        $features = $response->json('features');

        if (! is_array($features)) {
            throw new UnexpectedValueException(
                'USGS earthquake response does not contain a valid features collection.'
            );
        }

        return $features;
    }

    /**
     * @param  array<int, mixed>  $features
     * @return list<EarthquakeData>
     */
    private function mapFeatures(array $features): array
    {
        return array_values(
            array_filter(
                array_map(
                    fn (mixed $feature): ?EarthquakeData => is_array($feature)
                        ? $this->mapper->map($feature)
                        : null,
                    $features,
                )
            )
        );
    }

    private function cacheKey(EarthquakeSearchData $search): string
    {
        return sprintf(
            'earthquakes:usgs:%d:%d:%s:%d',
            $this->cacheTimestamp($search->startTime),
            $this->cacheTimestamp($search->endTime),
            $search->minimumMagnitude,
            $search->limit,
        );
    }

    private function cacheTimestamp(CarbonImmutable $time): int
    {
        $bucketSize = 5 * 60;

        return intdiv($time->timestamp, $bucketSize) * $bucketSize;
    }
}
