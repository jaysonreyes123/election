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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('table')->nullable();
            $table->string('columnname')->nullable();
            $table->string('tabid')->nullable();
            $table->string('label')->nullable();
            $table->string('type')->nullable();
            $table->string('datatype')->nullable();
            $table->string('default')->nullable();
            $table->json('picklist_value')->nullable();
            $table->string('length')->nullable();
            $table->string('decimals')->nullable();
            $table->string('sequence')->nullable();
            $table->string('blockid')->nullable();
            $table->string('mandatory')->nullable();
            $table->string('column')->nullable();
            $table->integer('editable')->default(1);
            $table->integer('presence')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
