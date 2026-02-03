<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('final_assignments', function (Blueprint $table) {
            $table->date('defense_date')->nullable();
            $table->time('defense_time')->nullable();
            $table->string('defense_venue')->nullable();
        });
    }

    public function down()
    {
        Schema::table('final_assignments', function (Blueprint $table) {
            $table->dropColumn(['defense_date', 'defense_time', 'defense_venue']);
        });
    }
};