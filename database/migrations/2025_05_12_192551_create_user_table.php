<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('category');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
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
            $table->string('status')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
