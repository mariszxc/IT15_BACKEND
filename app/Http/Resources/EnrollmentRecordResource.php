<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'studentId' => $this->student_id ? (string) $this->student_id : '',
            'studentNumber' => (string) ($this->student_number ?? ''),
            'studentName' => (string) ($this->student_name ?? ''),
            'batch' => (string) ($this->batch ?? ''),
            'submittedAt' => optional($this->submitted_at)->toISOString(),
            'submitted' => (bool) $this->submitted,
            'pending' => (bool) $this->pending,
            'approved' => (bool) $this->approved,
            'enrollmentStatus' => (string) ($this->enrollment_status ?? 'Enrolled'),
            'createdAt' => optional($this->created_at)->toISOString(),
            'updatedAt' => optional($this->updated_at)->toISOString(),
        ];
    }
}
