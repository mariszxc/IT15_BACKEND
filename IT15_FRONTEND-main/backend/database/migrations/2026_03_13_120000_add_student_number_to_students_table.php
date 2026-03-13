<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('students', 'student_number')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('student_number', 50)->nullable()->after('id');
            });
        }

        if (Schema::hasColumn('students', 'student_number')) {
            DB::statement(
                "UPDATE students SET student_number = CONCAT('LEGACY-', id) WHERE student_number = '' OR student_number IS NULL"
            );
        }

        $hasUnique = !empty(DB::select(
            "SHOW INDEX FROM students WHERE Key_name = 'students_student_number_unique'"
        ));

        if (!$hasUnique) {
            Schema::table('students', function (Blueprint $table) {
                $table->unique('student_number');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('students', 'student_number')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropUnique(['student_number']);
                $table->dropColumn('student_number');
            });
        }
    }
};
