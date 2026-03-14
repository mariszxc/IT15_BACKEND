<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class InjectDashboardIdentity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user() ?? User::query()->orderBy('id')->first();

        $name = $user?->name ?? 'Guest User';
        $email = $user?->email ?? 'guest@example.com';

        View::share('dashboardIdentity', [
            'name' => $name,
            'email' => $email,
            'school_year' => $this->currentSchoolYear(),
            'initials' => $this->initials($name),
        ]);

        return $next($request);
    }

    private function currentSchoolYear(): string
    {
        $year = now()->year;
        $month = now()->month;

        $startYear = $month >= 6 ? $year : $year - 1;

        return 'SY '.$startYear.'-'.($startYear + 1);
    }

    private function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $letters = [];

        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }

            $letters[] = strtoupper(substr($part, 0, 1));

            if (count($letters) === 2) {
                break;
            }
        }

        return implode('', $letters) ?: 'GU';
    }
}
