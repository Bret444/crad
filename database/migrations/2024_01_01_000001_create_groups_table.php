<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('department');
            $table->string('cluster');
            $table->integer('group_number');
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
            $table->integer('leader_member')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('groups');
    }
};