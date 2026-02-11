<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->unsignedBigInteger('assignment_id')->nullable()->after('cluster');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign(['assignment_id']);
            $table->dropColumn('assignment_id');
        });
    }
};
