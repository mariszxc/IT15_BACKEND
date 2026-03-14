<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherController extends Controller
{
    public function forecast(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'city' => ['nullable', 'string', 'max:120', 'required_without_all:latitude,longitude'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
        ], [
            'city.required_without_all' => 'Provide a city name or both latitude and longitude.',
            'latitude.required_with' => 'Latitude and longitude must both be provided.',
            'longitude.required_with' => 'Latitude and longitude must both be provided.',
        ]);

        $city = isset($validated['city']) ? trim($validated['city']) : null;
        $latitude = isset($validated['latitude']) ? (float) $validated['latitude'] : null;
        $longitude = isset($validated['longitude']) ? (float) $validated['longitude'] : null;

        if ($latitude !== null && $longitude !== null) {
            $location = $this->reverseGeocode($latitude, $longitude) ?? [
                'name' => 'Selected location',
                'country' => null,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];

            $searchKey = 'coords:'.round($latitude, 3).','.round($longitude, 3);
        } else {
            $location = $this->forwardGeocode((string) $city);

            if (!$location) {
                $fallback = $this->fallbackForecast(
                    (string) $city,
                    'Unable to resolve city coordinates right now. Showing fallback data.'
                );
                Cache::store('file')->put('weather:forecast:city:'.mb_strtolower((string) $city), $fallback, now()->addMinutes(10));

                return response()->json($fallback);
            }

            $latitude = (float) $location['latitude'];
            $longitude = (float) $location['longitude'];
            $searchKey = 'city:'.mb_strtolower((string) $city);
        }

        $cacheKey = 'weather:forecast:'.$searchKey;
        $cachedPayload = Cache::store('file')->get($cacheKey);

        [$forecast, $providerMessage] = $this->fetchForecast($latitude, $longitude);

        if (!$forecast) {
            if ($cachedPayload) {
                $cachedPayload['source'] = 'cache';
                $cachedPayload['message'] = $providerMessage ?? 'Weather provider is temporarily unavailable. Showing cached data.';

                return response()->json($cachedPayload);
            }

            return response()->json(
                $this->fallbackForecast(
                    $location['name'] ?? (string) $city,
                    $providerMessage ?? 'Weather provider is temporarily unavailable. Showing fallback data.',
                    $latitude,
                    $longitude
                )
            );
        }

        $payload = $this->formatForecastPayload($location, $forecast, $searchKey);
        Cache::store('file')->put($cacheKey, $payload, now()->addMinutes(10));

        return response()->json($payload);
    }

    private function forwardGeocode(string $city): ?array
    {
        try {
            $geocodeResponse = Http::timeout(15)
                ->acceptJson()
                ->get('https://geocoding-api.open-meteo.com/v1/search', [
                    'name' => $city,
                    'count' => 1,
                    'language' => 'en',
                    'format' => 'json',
                ]);
        } catch (Throwable) {
            return null;
        }

        if ($geocodeResponse->failed()) {
            return null;
        }

        $location = data_get($geocodeResponse->json(), 'results.0');

        return $location ?: null;
    }

    private function reverseGeocode(float $latitude, float $longitude): ?array
    {
        try {
            $reverseGeocodeResponse = Http::timeout(15)
                ->acceptJson()
                ->get('https://geocoding-api.open-meteo.com/v1/reverse', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'count' => 1,
                    'language' => 'en',
                    'format' => 'json',
                ]);
        } catch (Throwable) {
            return null;
        }

        if ($reverseGeocodeResponse->failed()) {
            return null;
        }

        return data_get($reverseGeocodeResponse->json(), 'results.0');
    }

    private function fetchForecast(float $latitude, float $longitude): array
    {
        try {
            $forecastResponse = Http::timeout(15)
                ->acceptJson()
                ->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'current' => 'temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m',
                    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max',
                    'timezone' => 'auto',
                    'forecast_days' => 5,
                ]);
        } catch (Throwable) {
            return [null, 'Unable to reach weather provider at the moment.'];
        }

        if ($forecastResponse->status() === 429) {
            return [null, 'Weather provider rate limit reached. Showing cached data when available.'];
        }

        if ($forecastResponse->failed()) {
            return [null, 'Unable to reach weather provider at the moment.'];
        }

        return [$forecastResponse->json(), null];
    }

    private function formatForecastPayload(array $location, array $forecast, string $searchKey): array
    {
        $currentCode = (int) data_get($forecast, 'current.weather_code', 0);
        $currentMeta = $this->weatherMeta($currentCode);

        $times = data_get($forecast, 'daily.time', []);
        $codes = data_get($forecast, 'daily.weather_code', []);
        $max = data_get($forecast, 'daily.temperature_2m_max', []);
        $min = data_get($forecast, 'daily.temperature_2m_min', []);
        $rain = data_get($forecast, 'daily.precipitation_probability_max', []);

        $days = [];

        foreach (array_slice($times, 0, 5) as $index => $day) {
            $code = (int) ($codes[$index] ?? 0);
            $meta = $this->weatherMeta($code);

            $days[] = [
                'date' => $day,
                'temperature_max' => $max[$index] ?? null,
                'temperature_min' => $min[$index] ?? null,
                'precipitation_probability' => $rain[$index] ?? null,
                'weather' => [
                    'code' => $code,
                    'description' => $meta['description'],
                    'icon' => $meta['icon'],
                ],
            ];
        }

        return [
            'message' => 'Weather loaded successfully.',
            'query' => $searchKey,
            'location' => [
                'name' => $location['name'] ?? 'Unknown location',
                'country' => $location['country'] ?? null,
                'latitude' => isset($location['latitude']) ? (float) $location['latitude'] : null,
                'longitude' => isset($location['longitude']) ? (float) $location['longitude'] : null,
                'timezone' => $forecast['timezone'] ?? config('app.timezone'),
            ],
            'current' => [
                'time' => data_get($forecast, 'current.time'),
                'temperature' => data_get($forecast, 'current.temperature_2m'),
                'humidity' => data_get($forecast, 'current.relative_humidity_2m'),
                'wind_speed' => data_get($forecast, 'current.wind_speed_10m'),
                'weather' => [
                    'code' => $currentCode,
                    'description' => $currentMeta['description'],
                    'icon' => $currentMeta['icon'],
                ],
            ],
            'forecast' => $days,
            'source' => 'open-meteo',
        ];
    }

    private function weatherMeta(int $code): array
    {
        return match (true) {
            $code === 0 => ['description' => 'Clear sky', 'icon' => 'sun'],
            in_array($code, [1, 2, 3], true) => ['description' => 'Partly cloudy', 'icon' => 'cloud-sun'],
            in_array($code, [45, 48], true) => ['description' => 'Fog', 'icon' => 'cloud-fog'],
            in_array($code, [51, 53, 55, 56, 57], true) => ['description' => 'Drizzle', 'icon' => 'cloud-drizzle'],
            in_array($code, [61, 63, 65, 66, 67, 80, 81, 82], true) => ['description' => 'Rain', 'icon' => 'cloud-rain'],
            in_array($code, [71, 73, 75, 77, 85, 86], true) => ['description' => 'Snow', 'icon' => 'cloud-snow'],
            in_array($code, [95, 96, 99], true) => ['description' => 'Thunderstorm', 'icon' => 'cloud-lightning'],
            default => ['description' => 'Unknown weather', 'icon' => 'cloud'],
        };
    }

    private function fallbackForecast(string $city, string $message, ?float $latitude = null, ?float $longitude = null): array
    {
        $today = now();
        $iconSet = [2, 3, 1, 2, 61];

        return [
            'message' => $message,
            'query' => 'fallback:'.mb_strtolower($city),
            'location' => [
                'name' => $city,
                'country' => null,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'timezone' => config('app.timezone'),
            ],
            'current' => [
                'time' => $today->toIso8601String(),
                'temperature' => 30,
                'humidity' => 72,
                'wind_speed' => 8,
                'weather' => [
                    'code' => 2,
                    'description' => $this->weatherMeta(2)['description'],
                    'icon' => $this->weatherMeta(2)['icon'],
                ],
            ],
            'forecast' => collect($iconSet)->map(function (int $code, int $index) use ($today) {
                $meta = $this->weatherMeta($code);

                return [
                    'date' => $today->copy()->addDays($index)->toDateString(),
                    'temperature_max' => [32, 31, 33, 32, 30][$index],
                    'temperature_min' => [24, 24, 25, 24, 23][$index],
                    'precipitation_probability' => [35, 40, 25, 30, 65][$index],
                    'weather' => [
                        'code' => $code,
                        'description' => $meta['description'],
                        'icon' => $meta['icon'],
                    ],
                ];
            })->values()->all(),
            'source' => 'fallback',
        ];
    }
}
