<?php

namespace App\Http\Controllers;

use App\Models\SchoolDay;
use App\Models\Student;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        [$rangeStart, $rangeEnd] = $this->schoolYearRange();
        $today = now()->endOfDay();
        $rangeEnd = $rangeEnd->greaterThan($today) ? $today : $rangeEnd;

        $monthTemplate = collect(CarbonPeriod::create($rangeStart->copy()->startOfMonth(), '1 month', $rangeEnd->copy()->startOfMonth()))
            ->mapWithKeys(fn (Carbon $date) => [$date->format('Y-m') => 0]);

        $monthlyEnrollmentData = DB::table('students')
            ->whereBetween('enrollment_date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->selectRaw("DATE_FORMAT(enrollment_date, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        $monthlyEnrollment = $monthTemplate
            ->merge($monthlyEnrollmentData)
            ->map(fn ($total, $month) => [
                'month' => $month,
                'total' => (int) $total,
            ])
            ->values();

        $courseDistribution = DB::table('courses')
            ->leftJoin('course_student', 'courses.id', '=', 'course_student.course_id')
            ->leftJoin('students', function ($join) use ($rangeStart, $rangeEnd) {
                $join->on('course_student.student_id', '=', 'students.id')
                    ->whereBetween('students.enrollment_date', [$rangeStart->toDateString(), $rangeEnd->toDateString()]);
            })
            ->selectRaw('courses.name as name, COUNT(students.id) as total_students')
            ->groupBy('courses.id', 'courses.name')
            ->orderByDesc('total_students')
            ->orderBy('courses.name')
            ->get();

        $studentsCount = Student::query()->whereBetween('enrollment_date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])->count();
        $coursesCount = DB::table('courses')->count();
        $enrollmentsCount = DB::table('course_student')
            ->join('students', 'students.id', '=', 'course_student.student_id')
            ->whereBetween('students.enrollment_date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->count();

        $attendanceData = DB::table('school_days')
            ->where('is_school_day', 1)
            ->whereBetween('date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, ROUND(AVG(attendance_rate), 2) as average_attendance_rate")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('average_attendance_rate', 'month');

        $attendancePatterns = $monthTemplate
            ->map(function ($defaultValue, $month) use ($attendanceData) {
                return [
                    'month' => $month,
                    'average_attendance_rate' => (float) ($attendanceData[$month] ?? $defaultValue),
                ];
            })
            ->values();

        return response()->json([
            'monthly_enrollment' => $monthlyEnrollment,
            'course_distribution' => $courseDistribution,
            'program_distribution' => $courseDistribution,
            'attendance_patterns' => $attendancePatterns,
            'totals' => [
                'students' => $studentsCount,
                'courses' => $coursesCount,
                'enrollments' => $enrollmentsCount,
            ],
            'range' => [
                'start' => $rangeStart->toDateString(),
                'end' => $rangeEnd->toDateString(),
            ],
            'generated_at' => now()->toIso8601String(),
            'summary' => [
                'students' => $studentsCount,
                'courses' => $coursesCount,
                'enrollments' => $enrollmentsCount,
                'school_days' => SchoolDay::query()->whereBetween('date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])->count(),
                'attendance_avg' => (float) (SchoolDay::query()
                    ->where('is_school_day', true)
                    ->whereBetween('date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
                    ->avg('attendance_rate') ?? 0),
            ],
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Vary', 'Authorization, Accept, Origin')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function schoolYearRange(): array
    {
        $now = now();
        $startYear = $now->month >= 6 ? $now->year : $now->year - 1;

        $start = Carbon::create($startYear, 6, 1)->startOfDay();
        $end = Carbon::create($startYear + 1, 5, 31)->endOfDay();

        return [$start, $end];
    }
}
