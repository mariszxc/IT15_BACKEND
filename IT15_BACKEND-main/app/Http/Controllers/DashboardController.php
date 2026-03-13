<?php

namespace App\Http\Controllers;

use App\Models\SchoolDay;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $monthlyEnrollment = Student::query()
            ->select(['enrollment_date'])
            ->get()
            ->groupBy(function (Student $student) {
                $value = $student->enrollment_date;

                return $value ? date('Y-m', strtotime((string) $value)) : null;
            })
            ->filter(fn ($items, $month) => !empty($month))
            ->map(fn ($items, $month) => [
                'month' => $month,
                'total' => $items->count(),
            ])
            ->values();

        $courseDistribution = DB::table('courses')
            ->leftJoin('course_student', 'courses.id', '=', 'course_student.course_id')
            ->selectRaw('courses.id, courses.code, courses.name, courses.department, COUNT(course_student.student_id) as total_students')
            ->groupBy('courses.id', 'courses.code', 'courses.name', 'courses.department')
            ->orderByDesc('total_students')
            ->get();

        $attendancePatterns = SchoolDay::query()
            ->where('is_school_day', true)
            ->select(['date', 'attendance_rate'])
            ->get()
            ->groupBy(function (SchoolDay $schoolDay) {
                $value = $schoolDay->date;

                return $value ? date('Y-m', strtotime((string) $value)) : null;
            })
            ->filter(fn ($items, $month) => !empty($month))
            ->map(function ($items, $month) {
                return [
                    'month' => $month,
                    'average_attendance_rate' => round($items->avg('attendance_rate'), 2),
                ];
            })
            ->values();

        return response()->json([
            'monthly_enrollment' => $monthlyEnrollment,
            'course_distribution' => $courseDistribution,
            'attendance_patterns' => $attendancePatterns,
            'summary' => [
                'students' => Student::count(),
                'school_days' => SchoolDay::count(),
                'attendance_avg' => (float) (SchoolDay::where('is_school_day', true)->avg('attendance_rate') ?? 0),
            ],
        ]);
    }
}
