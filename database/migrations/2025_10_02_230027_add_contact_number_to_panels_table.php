<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('panels', function (Blueprint $table) {
            if (!Schema::hasColumn('panels', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('expertise');
            }
        });
    }

    public function down()
    {
        Schema::table('panels', function (Blueprint $table) {
            if (Schema::hasColumn('panels', 'contact_number')) {
                $table->dropColumn('contact_number');
            }
        });
    }
};