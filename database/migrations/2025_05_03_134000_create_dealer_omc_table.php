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
        Schema::create('dealer_omc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dealer_id');
            $table->unsignedBigInteger('omc_id');
            $table->timestamps();

            $table->foreign('dealer_id')->references('id')->on('dealer')->onDelete('cascade');
            $table->foreign('omc_id')->references('id')->on('omc')->onDelete('cascade');

            $table->unique(['dealer_id', 'omc_id']); // prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_omc');
    }
};
