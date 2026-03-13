<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'maris@example.com'],
            [
                'name' => 'Maris',
                'password' => Hash::make('maris123'),
            ]
        );

        $courses = collect([
            ['name' => 'Computer Science', 'code' => 'CS101'],
            ['name' => 'Information Technology', 'code' => 'IT201'],
            ['name' => 'Nursing', 'code' => 'NS301'],
            ['name' => 'Civil Engineering', 'code' => 'CE401'],
            ['name' => 'Education', 'code' => 'ED501'],
        ])->map(fn (array $course) => Course::updateOrCreate(['code' => $course['code']], $course));

        $students = collect(range(1, 40))->map(function (int $index) {
            return Student::updateOrCreate(
                ['email' => "student{$index}@example.com"],
                [
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                ]
            );
        });

        foreach ($students as $student) {
            $course = $courses->random();
            $monthsAgo = random_int(0, 11);
            $enrolledAt = Carbon::now()->subMonths($monthsAgo)->startOfMonth()->addDays(random_int(0, 20));

            Enrollment::updateOrCreate(
                ['student_id' => $student->id, 'course_id' => $course->id],
                ['enrolled_at' => $enrolledAt]
            );
        }

        foreach (range(0, 19) as $offset) {
            $day = Carbon::now()->subWeekdays(19 - $offset)->toDateString();

            Attendance::updateOrCreate(
                ['school_day' => $day],
                ['attendance_rate' => random_int(82, 99)]
            );
        }
    }
}
