<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('member6_name')->nullable()->after('member5_student_id');
            $table->string('member6_student_id')->nullable()->after('member6_name');
            $table->string('member7_name')->nullable()->after('member6_student_id');
            $table->string('member7_student_id')->nullable()->after('member7_name');
            $table->string('member8_name')->nullable()->after('member7_student_id');
            $table->string('member8_student_id')->nullable()->after('member8_name');
            $table->string('member9_name')->nullable()->after('member8_student_id');
            $table->string('member9_student_id')->nullable()->after('member9_name');
            $table->string('member10_name')->nullable()->after('member9_student_id');
            $table->string('member10_student_id')->nullable()->after('member10_name');
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn([
                'member6_name', 'member6_student_id',
                'member7_name', 'member7_student_id',
                'member8_name', 'member8_student_id',
                'member9_name', 'member9_student_id',
                'member10_name', 'member10_student_id'
            ]);
        });
    }
};
