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
        Schema::create('user_privileges', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tabid');
            $table->foreign('tabid')->references('id')->on('tabs')->onDelete('cascade');

            $table->unsignedBigInteger('roleid');
            $table->foreign('roleid')->references('id')->on('roles')->onDelete('cascade');

            $table->tinyInteger('create')->nullable();
            $table->tinyInteger('edit')->nullable();
            $table->tinyInteger('delete')->nullable();
            $table->tinyInteger('import')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_privileges');
    }
};
