<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_number' => $this->student_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => trim($this->first_name.' '.$this->last_name),
            'email' => $this->email,
            'date_of_birth' => optional($this->date_of_birth)->toDateString(),
            'gender' => $this->gender,
            'department' => $this->department,
            'enrollment_date' => optional($this->enrollment_date)->toDateString(),
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
