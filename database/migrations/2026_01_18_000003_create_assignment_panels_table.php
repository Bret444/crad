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
        if (!Schema::hasTable('assignment_panels')) {
            Schema::create('assignment_panels', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('assignment_id');
                $table->unsignedBigInteger('panel_id');
                $table->string('name')->nullable();
                $table->json('availability')->nullable();
                $table->string('role')->nullable();
                $table->string('expertise')->nullable();
                $table->string('department')->nullable();
                $table->string('section')->nullable();
                $table->timestamps();

                $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
                $table->foreign('panel_id')->references('id')->on('panels')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_panels');
    }
};
