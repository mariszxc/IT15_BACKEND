<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_number', 50)->nullable()->index();
            $table->string('student_name', 255)->nullable();
            $table->string('batch', 120);
            $table->timestamp('submitted_at')->nullable();
            $table->boolean('submitted')->default(true);
            $table->boolean('pending')->default(false);
            $table->boolean('approved')->default(true);
            $table->string('enrollment_status', 50)->default('Enrolled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_records');
    }
};
