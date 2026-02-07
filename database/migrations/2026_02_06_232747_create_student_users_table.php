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
        Schema::create('student_users', function (Blueprint $table) {
            $table->id();
            $table->string('group_id')->unique();
            $table->string('email')->unique();
            $table->string('department');
            $table->string('cluster');
            $table->integer('group_number');
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_users');
    }
};
