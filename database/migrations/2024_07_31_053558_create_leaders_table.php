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
        Schema::create('leaders_table', function (Blueprint $table) {
            $table->id('leadersid');
            $table->integer('is_delete')->default(0);
            $table->text("barangay_name")->nullable();
            $table->text("precinct")->nullable();
            $table->text("area")->nullable();
            $table->integer("cluster_leader")->nullable();
            $table->integer("barangay_leader")->nullable();
            $table->integer("precinct_leader")->nullable();
            $table->integer("cell_political_leader")->nullable();
            $table->integer("street_leader")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaders');
    }
};
