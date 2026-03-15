<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'actor' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name ?? 'Current User',
                'email' => $this->user?->email ?? 'unknown@example.com',
            ],
            'action' => (string) ($this->action ?? ''),
            'entity' => (string) ($this->entity ?? ''),
            'description' => (string) ($this->description ?? ''),
            'metadata' => $this->metadata ?? [],
            'timestamp' => optional($this->occurred_at ?? $this->created_at)->toISOString(),
        ];
    }
}
