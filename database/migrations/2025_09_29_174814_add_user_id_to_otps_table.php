<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            if (!Schema::hasColumn('otps', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('otps', 'code')) {
                $table->string('code')->nullable();
            }
            if (!Schema::hasColumn('otps', 'expires_at')) {
                $table->timestamp('expires_at')->nullable();
            }
            if (!Schema::hasColumn('otps', 'used')) {
                $table->boolean('used')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};


