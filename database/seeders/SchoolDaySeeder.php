<?php

namespace Database\Seeders;

use App\Models\SchoolDay;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class SchoolDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year = (int) now()->format('Y');
        $start = Carbon::create($year, 1, 1);
        $end = Carbon::create($year, 12, 31);

        $holidayEvents = [
            "$year-01-01" => 'New Year Holiday',
            "$year-04-09" => 'Founders Day',
            "$year-05-01" => 'Labor Day',
            "$year-11-01" => 'School Break',
            "$year-12-25" => 'Christmas Day',
        ];

        $schoolEvents = [
            "$year-03-15" => 'Midterm Exams',
            "$year-06-15" => 'Science Fair',
            "$year-09-01" => 'First Semester Opening',
            "$year-10-20" => 'Sports Festival',
            "$year-12-10" => 'Final Exams',
        ];

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $dateString = $date->toDateString();
            $isWeekend = $date->isWeekend();
            $isHoliday = isset($holidayEvents[$dateString]);
            $isSchoolDay = !$isWeekend && !$isHoliday;
            $event = $holidayEvents[$dateString] ?? $schoolEvents[$dateString] ?? null;

            $attendanceCount = $isSchoolDay ? random_int(320, 495) : 0;
            $attendanceRate = $isSchoolDay ? round(($attendanceCount / 500) * 100, 2) : 0;

            SchoolDay::updateOrCreate(
                ['date' => $dateString],
                [
                    'is_school_day' => $isSchoolDay,
                    'is_holiday' => $isHoliday,
                    'event' => $event,
                    'attendance_count' => $attendanceCount,
                    'attendance_rate' => $attendanceRate,
                ]
            );
        }
    }
}
