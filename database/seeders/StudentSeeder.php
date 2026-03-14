<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        [$schoolYearStart, $schoolYearEnd] = $this->schoolYearToDateRange();

        Student::query()->delete();

        $faker = fake();
        $targetStudents = 500;
        $departments = [
            'Computer Science',
            'Information Technology',
            'Mechanical Engineering',
            'Humanities',
            'Business',
            'Social Science',
        ];

        $courseIds = Course::pluck('id')->all();
        for ($index = 0; $index < $targetStudents; $index++) {
            $student = Student::create([
                'student_number' => null,
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'date_of_birth' => $faker->dateTimeBetween('-24 years', '-16 years')->format('Y-m-d'),
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'enrollment_date' => Carbon::instance($faker->dateTimeBetween($schoolYearStart, $schoolYearEnd))->format('Y-m-d'),
                'department' => $faker->randomElement($departments),
                'address' => $faker->streetAddress().', '.$faker->city(),
                'phone_number' => $faker->phoneNumber(),
            ]);

            if (!empty($courseIds)) {
                $student->courses()->sync($faker->randomElements($courseIds, random_int(1, 4)));
            }
        }

        $students = Student::query()->orderBy('id')->get(['id']);

        if (!empty($courseIds)) {
            foreach ($students as $student) {
                $student->courses()->sync($faker->randomElements($courseIds, random_int(1, 4)));
            }
        }

        foreach ($students as $student) {
            $student->update([
                'student_number' => 'TMP'.str_pad((string) $student->id, 9, '0', STR_PAD_LEFT),
                'email' => 'tmp_student_'.$student->id.'@example.com',
            ]);
        }

        foreach ($students->values() as $position => $student) {
            $sequence = $position + 1;

            $student->update([
                'student_number' => str_pad((string) $sequence, 6, '0', STR_PAD_LEFT),
                'email' => 'student'.$sequence.'@example.com',
            ]);
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
