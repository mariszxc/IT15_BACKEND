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
            ['code' => 'BSIT', 'name' => 'Bachelor of Science in Information Technology', 'department' => 'College of Computing', 'credits' => 3],
            ['code' => 'BSIS', 'name' => 'Bachelor of Science in Information Systems', 'department' => 'College of Computing', 'credits' => 3],
            ['code' => 'BSCS', 'name' => 'Bachelor of Science in Computer Science', 'department' => 'College of Computing', 'credits' => 3],
            ['code' => 'BSCPE', 'name' => 'Bachelor of Science in Computer Engineering', 'department' => 'College of Engineering', 'credits' => 3],
            ['code' => 'BSCE', 'name' => 'Bachelor of Science in Civil Engineering', 'department' => 'College of Engineering', 'credits' => 3],
            ['code' => 'BSEE', 'name' => 'Bachelor of Science in Electrical Engineering', 'department' => 'College of Engineering', 'credits' => 3],
            ['code' => 'BSME', 'name' => 'Bachelor of Science in Mechanical Engineering', 'department' => 'College of Engineering', 'credits' => 3],
            ['code' => 'BSAE', 'name' => 'Bachelor of Science in Agricultural Engineering', 'department' => 'College of Engineering', 'credits' => 3],
            ['code' => 'BSBA', 'name' => 'Bachelor of Science in Business Administration', 'department' => 'College of Business and Accountancy', 'credits' => 3],
            ['code' => 'BSA', 'name' => 'Bachelor of Science in Accountancy', 'department' => 'College of Business and Accountancy', 'credits' => 3],
            ['code' => 'BSENTREP', 'name' => 'Bachelor of Science in Entrepreneurship', 'department' => 'College of Business and Accountancy', 'credits' => 3],
            ['code' => 'BSHM', 'name' => 'Bachelor of Science in Hospitality Management', 'department' => 'College of Hospitality and Tourism', 'credits' => 3],
            ['code' => 'BSTM', 'name' => 'Bachelor of Science in Tourism Management', 'department' => 'College of Hospitality and Tourism', 'credits' => 3],
            ['code' => 'BEED', 'name' => 'Bachelor of Elementary Education', 'department' => 'College of Education', 'credits' => 3],
            ['code' => 'BSED', 'name' => 'Bachelor of Secondary Education', 'department' => 'College of Education', 'credits' => 3],
            ['code' => 'BSN', 'name' => 'Bachelor of Science in Nursing', 'department' => 'College of Nursing and Allied Health', 'credits' => 3],
            ['code' => 'BSPHARM', 'name' => 'Bachelor of Science in Pharmacy', 'department' => 'College of Nursing and Allied Health', 'credits' => 3],
            ['code' => 'BSCRIM', 'name' => 'Bachelor of Science in Criminology', 'department' => 'College of Criminal Justice Education', 'credits' => 3],
            ['code' => 'ABCOMM', 'name' => 'Bachelor of Arts in Communication', 'department' => 'College of Arts and Sciences', 'credits' => 3],
            ['code' => 'BSPSYCH', 'name' => 'Bachelor of Science in Psychology', 'department' => 'College of Arts and Sciences', 'credits' => 3],
        ];

        $courseCodes = collect($courses)->pluck('code')->all();

        Course::query()
            ->whereNotIn('code', $courseCodes)
            ->delete();

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
