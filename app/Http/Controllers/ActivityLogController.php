<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 25);
        $perPage = max(1, min($perPage, 500));

        $query = ActivityLog::query()
            ->with('user:id,name,email')
            ->where('user_id', $request->user()?->id)
            ->latest('occurred_at')
            ->latest();

        return ActivityLogResource::collection($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'max:120'],
            'entity' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:2000'],
            'metadata' => ['nullable', 'array'],
            'timestamp' => ['nullable', 'date'],
        ]);

        $entry = ActivityLog::create([
            'user_id' => $request->user()?->id,
            'action' => $validated['action'],
            'entity' => $validated['entity'],
            'description' => $validated['description'],
            'metadata' => $validated['metadata'] ?? [],
            'occurred_at' => $validated['timestamp'] ?? now(),
        ]);

        return new ActivityLogResource($entry->load('user:id,name,email'));
    }
}
