<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('station', function (Blueprint $table) {
            $table->id();
            $table->string('stationName');
            $table->string('district');
            $table->string('chiefdom');
            $table->text('dealer_id');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('device')->nullable();
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_login')->nullable();
            $table->string('userName')->nullable();
            $table->unsignedBigInteger('userId')->nullable();
            $table->string('userRole')->nullable();
            $table->string('editedUserName')->nullable();
            $table->unsignedBigInteger('editedUserId')->nullable();
            $table->string('editedUserRole')->nullable();
            $table->string('editedDevice')->nullable();
            $table->timestamp('editedTime')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('station');
    }
};
