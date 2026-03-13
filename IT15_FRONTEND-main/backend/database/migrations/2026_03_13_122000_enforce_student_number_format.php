<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        DB::statement("UPDATE students SET student_number = LPAD(id, 6, '0')");

        $hasUnique = !empty(DB::select(
            "SHOW INDEX FROM students WHERE Key_name = 'students_student_number_unique'"
        ));

        if ($hasUnique) {
            DB::statement("ALTER TABLE students DROP INDEX students_student_number_unique");
        }

        DB::statement("ALTER TABLE students MODIFY student_number VARCHAR(6) NOT NULL");
        DB::statement("ALTER TABLE students ADD UNIQUE students_student_number_unique (student_number)");
    }

    public function down(): void
    {
        if (!Schema::hasTable('students')) {
            return;
        }

        $hasUnique = !empty(DB::select(
            "SHOW INDEX FROM students WHERE Key_name = 'students_student_number_unique'"
        ));

        if ($hasUnique) {
            DB::statement("ALTER TABLE students DROP INDEX students_student_number_unique");
        }

        DB::statement("ALTER TABLE students MODIFY student_number VARCHAR(50) NULL");
    }
};
