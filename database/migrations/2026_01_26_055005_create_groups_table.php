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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_id')->unique();
            $table->string('password');
            $table->string('department');
            $table->string('member1_name')->nullable();
            $table->string('member1_student_id')->nullable();
            $table->string('member2_name')->nullable();
            $table->string('member2_student_id')->nullable();
            $table->string('member3_name')->nullable();
            $table->string('member3_student_id')->nullable();
            $table->string('member4_name')->nullable();
            $table->string('member4_student_id')->nullable();
            $table->string('member5_name')->nullable();
            $table->string('member5_student_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
