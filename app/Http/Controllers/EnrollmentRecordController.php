<?php

namespace App\Http\Controllers;

use App\Http\Resources\EnrollmentRecordResource;
use App\Models\EnrollmentRecord;
use App\Models\Student;
use Illuminate\Http\Request;

class EnrollmentRecordController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 25);
        $perPage = max(1, min($perPage, 500));

        $query = EnrollmentRecord::query()->latest();

        if ($request->filled('student_id')) {
            $query->where('student_id', (int) $request->query('student_id'));
        }

        if ($request->filled('student_number')) {
            $query->where('student_number', (string) $request->query('student_number'));
        }

        return EnrollmentRecordResource::collection($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'studentId' => ['nullable', 'integer', 'exists:students,id'],
            'studentNumber' => ['nullable', 'string', 'max:50'],
            'studentName' => ['nullable', 'string', 'max:255'],
            'batch' => ['nullable', 'string', 'max:120'],
            'submittedAt' => ['nullable', 'date'],
            'submitted' => ['nullable', 'boolean'],
            'pending' => ['nullable', 'boolean'],
            'approved' => ['nullable', 'boolean'],
            'enrollmentStatus' => ['nullable', 'string', 'max:50'],
        ]);

        $studentId = isset($validated['studentId']) ? (int) $validated['studentId'] : null;
        $studentNumber = isset($validated['studentNumber']) ? trim((string) $validated['studentNumber']) : null;

        if (! $studentId && ! $studentNumber) {
            return response()->json([
                'message' => 'studentId or studentNumber is required.',
            ], 422);
        }

        $student = null;

        if ($studentId) {
            $student = Student::find($studentId);
        } elseif ($studentNumber) {
            $student = Student::query()->where('student_number', $studentNumber)->first();
        }

        $target = EnrollmentRecord::query()
            ->when($student?->id, fn ($query) => $query->where('student_id', $student->id))
            ->when(! $student?->id && $studentNumber, fn ($query) => $query->where('student_number', $studentNumber))
            ->first();

        $now = now();

        $payload = [
            'user_id' => $request->user()?->id,
            'student_id' => $student?->id,
            'student_number' => $student?->student_number ?? $studentNumber,
            'student_name' => $validated['studentName']
                ?? ($student ? trim($student->first_name.' '.$student->last_name) : null),
            'batch' => $validated['batch']
                ?? now()->toDateString(),
            'submitted_at' => $validated['submittedAt'] ?? $now,
            'submitted' => array_key_exists('submitted', $validated) ? (bool) $validated['submitted'] : true,
            'pending' => array_key_exists('pending', $validated) ? (bool) $validated['pending'] : false,
            'approved' => array_key_exists('approved', $validated) ? (bool) $validated['approved'] : true,
            'enrollment_status' => $validated['enrollmentStatus']
                ?? ((array_key_exists('approved', $validated) && ! $validated['approved']) ? 'Pending' : 'Enrolled'),
        ];

        if ($target) {
            $target->update($payload);
            return new EnrollmentRecordResource($target->fresh());
        }

        $record = EnrollmentRecord::create($payload);

        return new EnrollmentRecordResource($record);
    }
}
