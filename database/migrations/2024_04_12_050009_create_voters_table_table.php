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
        Schema::create('voters_table', function (Blueprint $table) {
            $table->id('votersid');
            $table->integer('is_delete')->default(0);
            $table->integer('no')->nullable();
            $table->string('legend')->nullable();
            $table->string('voters_name')->nullable();
            $table->text('voters_address')->nullable();
            $table->string('precinct')->nullable();
            $table->string('barangay')->nullable();
            $table->integer('vote')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters_table');
    }
};
