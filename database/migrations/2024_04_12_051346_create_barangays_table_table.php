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
        Schema::create('barangays_table', function (Blueprint $table) {
            $table->id('barangaysid');
            $table->integer('is_delete')->default(0);
            $table->string('name')->nullable();
            $table->string('map_image')->nullable();
            $table->integer('total_number_streets')->nullable();
            $table->integer('total_number_population')->nullable();
            $table->integer('total_number_registered_voters')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangays_table');
    }
};
