<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This migration is no longer needed - ID column is already properly configured
        // Keeping as no-op to avoid breaking existing databases
    }

    public function down(): void
    {
        // Cannot reverse this migration safely
    }
};