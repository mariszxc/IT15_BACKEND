<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function current(Request $request): JsonResponse
    {
        if (! $this->hasApiKey()) {
            return response()->json([
                'message' => 'Weather API key is not configured on the server.',
            ], 503);
        }

        $validated = $request->validate([
            'city' => ['nullable', 'string', 'max:80'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lon' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $query = $this->buildQuery($validated);

        if (! $query) {
            return response()->json(['message' => 'Provide either city or valid coordinates.'], 422);
        }

        return $this->requestWeather('/weather', $query);
    }

    public function forecast(Request $request): JsonResponse
    {
        if (! $this->hasApiKey()) {
            return response()->json([
                'message' => 'Weather API key is not configured on the server.',
            ], 503);
        }

        $validated = $request->validate([
            'city' => ['nullable', 'string', 'max:80'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lon' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $query = $this->buildQuery($validated);

        if (! $query) {
            return response()->json(['message' => 'Provide either city or valid coordinates.'], 422);
        }

        return $this->requestWeather('/forecast', $query);
    }

    private function buildQuery(array $validated): ?array
    {
        $query = [
            'appid' => config('services.weather.api_key'),
            'units' => config('services.weather.units'),
        ];

        if (! empty($validated['city'])) {
            $query['q'] = trim(strip_tags($validated['city']));
            return $query;
        }

        if (isset($validated['lat'], $validated['lon'])) {
            $query['lat'] = $validated['lat'];
            $query['lon'] = $validated['lon'];
            return $query;
        }

        return null;
    }

    private function hasApiKey(): bool
    {
        return ! empty(config('services.weather.api_key'));
    }

    private function requestWeather(string $endpoint, array $query): JsonResponse
    {
        try {
            $response = Http::baseUrl(config('services.weather.base_url'))
                ->timeout(12)
                ->acceptJson()
                ->get($endpoint, $query);

            if ($response->status() === 429) {
                return response()->json([
                    'message' => 'Weather provider rate limit reached. Please retry shortly.',
                ], 429);
            }

            if ($response->failed()) {
                throw new RequestException($response);
            }

            return response()->json($response->json());
        } catch (ConnectionException) {
            return response()->json([
                'message' => 'Unable to reach weather provider at the moment.',
            ], 503);
        } catch (RequestException $exception) {
            return response()->json([
                'message' => 'Failed to fetch weather data.',
                'provider_error' => $exception->response?->json('message') ?? null,
            ], $exception->response?->status() ?? 500);
        }
    }
}
