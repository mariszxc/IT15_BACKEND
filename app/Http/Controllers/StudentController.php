<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 25);
        $perPage = max(1, min($perPage, 500));

        $students = Student::with('courses:id,name,code')->latest()->paginate($perPage);

        return StudentResource::collection($students);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => ['nullable', 'string', 'max:50', Rule::unique('students', 'student_number')],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'enrollment_date' => ['nullable', 'date'],
            'department' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'course_ids' => ['sometimes', 'array'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $payload = array_merge([
            'date_of_birth' => now()->subYears(18)->toDateString(),
            'gender' => 'other',
            'enrollment_date' => now()->toDateString(),
            'department' => 'Undeclared',
            'address' => 'TBD',
            'phone_number' => 'N/A',
        ], collect($validated)->except('course_ids')->toArray());

        if (empty($payload['student_number'])) {
            $payload['student_number'] = $this->generateStudentNumber();
        }

        $student = Student::create($payload);

        if (!empty($validated['course_ids'])) {
            $student->courses()->sync($validated['course_ids']);
        }

        return new StudentResource($student->load('courses:id,name,code'));
    }

    public function show(Student $student)
    {
        return new StudentResource($student->load('courses:id,name,code'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_number' => ['sometimes', 'nullable', 'string', 'max:50', Rule::unique('students', 'student_number')->ignore($student->id)],
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student->id)],
            'date_of_birth' => ['sometimes', 'required', 'date', 'before:today'],
            'gender' => ['sometimes', 'required', Rule::in(['male', 'female', 'other'])],
            'enrollment_date' => ['sometimes', 'required', 'date'],
            'department' => ['sometimes', 'required', 'string', 'max:255'],
            'address' => ['sometimes', 'required', 'string', 'max:500'],
            'phone_number' => ['sometimes', 'required', 'string', 'max:50'],
            'course_ids' => ['sometimes', 'array'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $student->update(collect($validated)->except('course_ids')->toArray());

        if (array_key_exists('course_ids', $validated)) {
            $student->courses()->sync($validated['course_ids']);
        }

        return new StudentResource($student->load('courses:id,name,code'));
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json(['message' => 'Student deleted.']);
    }

    private function generateStudentNumber(): string
    {
        $max = Student::query()
            ->pluck('student_number')
            ->filter(fn ($value) => is_string($value) && preg_match('/^\d{1,6}$/', $value))
            ->map(fn ($value) => (int) $value)
            ->max() ?? 0;

        return str_pad((string) ($max + 1), 6, '0', STR_PAD_LEFT);
    }
}
