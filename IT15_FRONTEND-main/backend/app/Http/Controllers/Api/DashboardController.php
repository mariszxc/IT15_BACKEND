<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function metrics(): JsonResponse
    {
        $enrollmentTrends = Enrollment::query()
            ->selectRaw("DATE_FORMAT(enrolled_at, '%Y-%m') as month")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $courseDistribution = Course::query()
            ->leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->select('courses.name')
            ->selectRaw('COUNT(enrollments.id) as total')
            ->groupBy('courses.id', 'courses.name')
            ->orderByDesc('total')
            ->get();

        $attendancePatterns = Attendance::query()
            ->select('school_day')
            ->selectRaw('ROUND(AVG(attendance_rate), 2) as rate')
            ->groupBy('school_day')
            ->orderBy('school_day')
            ->get();

        $totals = [
            'students' => (int) DB::table('students')->count(),
            'courses' => (int) DB::table('courses')->count(),
            'enrollments' => (int) DB::table('enrollments')->count(),
        ];

        return response()->json([
            'totals' => $totals,
            'enrollmentTrends' => $enrollmentTrends,
            'courseDistribution' => $courseDistribution,
            'attendancePatterns' => $attendancePatterns,
        ]);
    }
}
