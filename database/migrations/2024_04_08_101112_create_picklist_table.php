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
        Schema::create('picklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tabid');
            $table->foreign('tabid')->references('id')->on('tabs')->onDelete('cascade');

            $table->unsignedBigInteger('fieldid');
            $table->foreign('fieldid')->references('id')->on('fields')->onDelete('cascade');

            $table->string('name');
            $table->integer('sequence');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picklist');
    }
};
