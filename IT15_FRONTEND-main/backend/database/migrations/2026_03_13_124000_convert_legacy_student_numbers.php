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

        DB::statement(
            "UPDATE students SET student_number = LPAD(CAST(REPLACE(student_number, 'LEGACY-', '') AS UNSIGNED), 6, '0') WHERE student_number LIKE 'LEGACY-%'"
        );

        DB::statement(
            "UPDATE students SET student_number = LPAD(CAST(student_number AS UNSIGNED), 6, '0') WHERE student_number REGEXP '^[0-9]+$'"
        );
    }

    public function down(): void
    {
        // No down migration for legacy conversion.
    }
};
