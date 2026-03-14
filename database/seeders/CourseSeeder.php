<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            ['code' => 'CS101', 'name' => 'Intro to Programming', 'department' => 'Computer Science', 'credits' => 3],
            ['code' => 'CS201', 'name' => 'Data Structures', 'department' => 'Computer Science', 'credits' => 4],
            ['code' => 'CS301', 'name' => 'Database Systems', 'department' => 'Computer Science', 'credits' => 3],
            ['code' => 'CS305', 'name' => 'Web Development', 'department' => 'Computer Science', 'credits' => 3],
            ['code' => 'MATH101', 'name' => 'College Algebra', 'department' => 'Mathematics', 'credits' => 3],
            ['code' => 'MATH205', 'name' => 'Calculus I', 'department' => 'Mathematics', 'credits' => 4],
            ['code' => 'MATH220', 'name' => 'Statistics', 'department' => 'Mathematics', 'credits' => 3],
            ['code' => 'PHY101', 'name' => 'General Physics', 'department' => 'Physics', 'credits' => 4],
            ['code' => 'PHY210', 'name' => 'Modern Physics', 'department' => 'Physics', 'credits' => 3],
            ['code' => 'CHEM101', 'name' => 'General Chemistry', 'department' => 'Chemistry', 'credits' => 4],
            ['code' => 'BIO101', 'name' => 'Biology Fundamentals', 'department' => 'Biology', 'credits' => 3],
            ['code' => 'ENG101', 'name' => 'English Composition', 'department' => 'Humanities', 'credits' => 3],
            ['code' => 'ENG220', 'name' => 'World Literature', 'department' => 'Humanities', 'credits' => 3],
            ['code' => 'HIST101', 'name' => 'World History', 'department' => 'Humanities', 'credits' => 3],
            ['code' => 'ECON101', 'name' => 'Microeconomics', 'department' => 'Business', 'credits' => 3],
            ['code' => 'ECON102', 'name' => 'Macroeconomics', 'department' => 'Business', 'credits' => 3],
            ['code' => 'BUS201', 'name' => 'Principles of Management', 'department' => 'Business', 'credits' => 3],
            ['code' => 'PSY101', 'name' => 'Introduction to Psychology', 'department' => 'Social Science', 'credits' => 3],
            ['code' => 'SOC101', 'name' => 'Introduction to Sociology', 'department' => 'Social Science', 'credits' => 3],
            ['code' => 'PE101', 'name' => 'Health and Wellness', 'department' => 'Physical Education', 'credits' => 2],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['code' => $course['code']],
                [
                    'name' => $course['name'],
                    'department' => $course['department'],
                    'credits' => $course['credits'],
                    'description' => $course['name'].' course in '.$course['department'].'.',
                ]
            );
        }
    }
}
