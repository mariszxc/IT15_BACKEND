<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            "UPDATE students SET first_name = CONCAT(UCASE(LEFT(first_name, 1)), LCASE(SUBSTRING(first_name, 2)))"
        );
        DB::statement(
            "UPDATE students SET last_name = CONCAT(UCASE(LEFT(last_name, 1)), LCASE(SUBSTRING(last_name, 2)))"
        );
    }

    public function down(): void
    {
        // No down migration for name normalization.
    }
};
