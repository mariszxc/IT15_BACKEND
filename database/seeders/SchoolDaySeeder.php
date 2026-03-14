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
        [$start, $end] = $this->schoolYearToDateRange();
        $startYear = (int) $start->format('Y');
        $nextYear = $startYear + 1;

        SchoolDay::query()
            ->where('date', '<', $start->toDateString())
            ->orWhere('date', '>', $end->toDateString())
            ->delete();

        $holidayEvents = [
            "$nextYear-01-01" => 'New Year Holiday',
            "$nextYear-04-09" => 'Araw ng Kagitingan',
            "$nextYear-05-01" => 'Labor Day',
            "$startYear-11-01" => 'All Saints Day',
            "$startYear-11-30" => 'Bonifacio Day',
            "$startYear-12-25" => 'Christmas Day',
            "$startYear-12-30" => 'Rizal Day',
        ];

        $schoolEvents = [
            "$startYear-06-10" => 'First Semester Opening',
            "$startYear-08-22" => 'Academic Fest',
            "$startYear-10-18" => 'Intramurals Week Opening',
            "$startYear-10-19" => 'Intramurals - Basketball Finals',
            "$startYear-10-20" => 'Intramurals - Cultural Showcase',
            "$startYear-12-10" => 'Final Exams',
            "$nextYear-02-14" => 'Feb-ibig Campus Celebration',
            "$nextYear-03-15" => 'Midterm Exams',
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

    private function schoolYearToDateRange(): array
    {
        $now = now();
        $startYear = $now->month >= 6 ? $now->year : $now->year - 1;

        $start = Carbon::create($startYear, 6, 1)->startOfDay();

        return [$start, $now->endOfDay()];
    }
}
