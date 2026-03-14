<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectAuthContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $response instanceof JsonResponse) {
            return $response;
        }

        $user = $request->user();

        if (! $user) {
            return $response;
        }

        $data = $response->getData(true);

        if (! is_array($data) || array_key_exists('auth', $data)) {
            return $response;
        }

        $data['auth'] = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'school_year' => $this->currentSchoolYear(),
        ];

        $response->setData($data);

        return $response;
    }

    private function currentSchoolYear(): string
    {
        $year = now()->year;
        $month = now()->month;

        $startYear = $month >= 6 ? $year : $year - 1;

        return 'SY '.$startYear.'-'.($startYear + 1);
    }
}
