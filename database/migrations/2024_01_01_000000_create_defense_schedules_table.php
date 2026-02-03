<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('defense_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('department');
            $table->string('section');
            $table->string('group_id');
            $table->enum('defense_type', ['PRE-ORAL', 'FINAL DEFENSE', 'REDEFENSE']);
            $table->string('original_defense_type')->nullable(); // For redefense tracking
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->date('defense_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('set_letter', 1)->nullable();
            $table->enum('status', ['Pending', 'Scheduled', 'Passed', 'Failed', 'Re-defense'])->default('Pending');
            $table->json('panel_data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['department', 'section', 'defense_type']);
            $table->unique(['group_id', 'department', 'section', 'defense_type'], 'defense_schedules_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('defense_schedules');
    }
};