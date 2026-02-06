<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('professor_id')->unique();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('professor');
            $table->string('picture')->nullable();
            $table->longText('face_descriptor')->nullable();
            $table->dateTime('face_enrolled_at')->nullable();
            $table->dateTime('attendance_terms_accepted_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('employment_type')->default('Full-Time');
            $table->string('department')->default('BSIT');
            $table->text('skills')->nullable();
            $table->text('experiences')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};
