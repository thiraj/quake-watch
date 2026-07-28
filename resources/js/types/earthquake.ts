export interface Earthquake {
    id: string;
    location: string;
    magnitude: number;
    occurredAt: string;
    longitude: number;
    latitude: number;
    depth: number;
    url: string;
}

export interface EarthquakeSummary {
    total: number;
    strongestMagnitude: number | null;
    averageMagnitude: number | null;
}

export type EarthquakePeriod = 'hour' | 'day' | 'week';

export interface EarthquakeFilters {
    period: EarthquakePeriod;
    minimumMagnitude: number;
}
