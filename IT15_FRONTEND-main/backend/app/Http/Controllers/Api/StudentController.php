<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected function normalizeName(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return $value;
        }

        return ucfirst(strtolower($value));
    }

    protected function generateStudentNumber(): string
    {
        $maxNumber = Student::query()
            ->whereRaw("student_number REGEXP '^[0-9]+$'")
            ->selectRaw('MAX(CAST(student_number AS UNSIGNED)) as max_number')
            ->value('max_number');

        $nextNumber = (int) $maxNumber + 1;

        do {
            $candidate = str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
            $exists = Student::where('student_number', $candidate)->exists();
            $nextNumber++;
        } while ($exists);

        return $candidate;
    }

    public function index()
    {
        $students = Student::query()
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return response()->json([
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
        ]);

        $validated['first_name'] = $this->normalizeName($validated['first_name']);
        $validated['last_name'] = $this->normalizeName($validated['last_name']);
        $validated['student_number'] = $this->generateStudentNumber();

        $student = Student::create($validated);

        return response()->json([
            'message' => 'Student saved successfully.',
            'student' => $student,
        ], 201);
    }

    public function show(Student $student)
    {
        return response()->json([
            'student' => $student,
        ]);
    }
}
