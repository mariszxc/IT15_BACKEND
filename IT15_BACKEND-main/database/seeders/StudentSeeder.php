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
        $faker = fake();
        $departments = [
            'Computer Science',
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'Humanities',
            'Business',
            'Social Science',
        ];

        $courseIds = Course::pluck('id')->all();

        for ($index = 0; $index < 500; $index++) {
            $student = Student::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'date_of_birth' => $faker->dateTimeBetween('-24 years', '-16 years')->format('Y-m-d'),
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'enrollment_date' => Carbon::instance($faker->dateTimeBetween('-18 months', 'now'))->format('Y-m-d'),
                'department' => $faker->randomElement($departments),
                'address' => $faker->streetAddress().', '.$faker->city(),
                'phone_number' => $faker->phoneNumber(),
            ]);

            if (!empty($courseIds)) {
                $student->courses()->sync($faker->randomElements($courseIds, random_int(1, 4)));
            }
        }
    }
}
