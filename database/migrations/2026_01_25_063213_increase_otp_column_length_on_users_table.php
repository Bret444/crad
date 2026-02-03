<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Clear existing OTP data to avoid truncation errors
        DB::table('users')->update(['otp' => null]);
        
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp', 255)->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp', 6)->nullable()->change();
        });
    }
};
