# QuakeWatch

QuakeWatch is a small Laravel and Vue application for exploring recent earthquake activity around the world using data from the USGS Earthquake Hazards Program.

The project was built as a focused technical exercise, with an emphasis on clean structure, external API integration, sensible separation of responsibilities, and a simple user experience.

## What I Built

The application provides a dashboard showing recent earthquake activity.

It includes:

* Total number of earthquakes returned
* Strongest magnitude
* Average magnitude
* Recent earthquake results with location, magnitude, depth, time, and a link to the original USGS event
* Time range filters for:

  * Last hour
  * Last 24 hours
  * Last 7 days
* Minimum magnitude filtering

The application uses Laravel with Inertia and Vue 3 for the frontend.

## Why I Chose the USGS API

I chose the USGS Earthquake Hazards API because it provides real-world, regularly updated data and works well for a small monitoring and exploration application.

It gave me an opportunity to work with an external API while also considering data transformation, validation, caching, filtering, error handling, and how the information should be presented to the user.

The API was selected from the public API list provided with the exercise.

## Technical Approach

I kept the application structure intentionally small while separating the external integration from the application logic.

The main flow is:

```text
HTTP Request
-> EarthquakeIndexRequest
-> EarthquakeSearchData
-> EarthquakeController
-> EarthquakeService
-> EarthquakeProvider
-> UsgsEarthquakeProvider
-> USGS API
```

`EarthquakeProvider` defines the contract required by the application.

`UsgsEarthquakeProvider` contains the implementation for communicating with USGS. This means `EarthquakeService` does not depend directly on USGS and another provider could be introduced later by implementing the same contract.

The USGS response is also mapped into application-owned `EarthquakeData` objects instead of passing the external GeoJSON structure through the rest of the application.

This keeps USGS-specific response details inside the integration layer.

### Caching

Requests to USGS are cached for a short period to reduce unnecessary requests to the external service.

The raw USGS feature data is cached and then mapped into `EarthquakeData` objects when it is used by the application.

### Frontend

The frontend uses Vue 3, TypeScript, Inertia, and Tailwind CSS.

Filters are submitted through Inertia using query parameters, for example:

```text
/earthquakes?period=day&minimum_magnitude=3
```

This keeps filtered views refreshable and shareable without adding a separate API for the Vue frontend.

## Trade-offs

I kept the scope focused in line with the suggested 1-3 hour timeframe.

Each request currently retrieves a maximum of 100 earthquake records. When the result reaches this limit, the dashboard displays the count as `100+` rather than suggesting that exactly 100 earthquakes occurred.

I chose not to add authentication, database persistence, pagination, or an interactive map because they were not necessary for the core use case.

USGS remains the source of truth, so earthquake records are not stored locally.

I also kept the provider abstraction simple. There is currently only one provider, so I did not introduce a factory or additional layers that the application does not yet need.

## What I Would Improve With More Time

With more time I would consider:

* An interactive map showing earthquake locations
* Pagination or progressive loading for larger result sets
* A custom date and time range
* Improved magnitude and severity visualisation
* More detailed loading and external API error states
* Additional automated test coverage for validation, caching, and malformed upstream responses
* Retry handling for appropriate temporary API failures
* Further accessibility and responsive UI improvements

## Running the Project

Install the PHP dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

Install the frontend dependencies:

```bash
npm install
```

Start the development environment:

```bash
composer run dev
```

Then open:

```text
/earthquakes
```

## Useful Commands

Run the tests:

```bash
php artisan test
```

Format PHP code:

```bash
./vendor/bin/pint
```

Run frontend linting:

```bash
npm run lint
```

Build the frontend assets:

```bash
npm run build
```

## Tech Stack

* Laravel
* PHP
* Vue 3
* TypeScript
* Inertia
* Tailwind CSS
* Laravel HTTP Client
* Laravel Cache
* USGS Earthquake Hazards API
