<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('omc', function (Blueprint $table) {
            $table->string('userName')->nullable();
            $table->string('userId')->nullable();
            $table->string('userRole')->nullable();
            $table->string('editedUserName')->nullable();
            $table->string('editedUserId')->nullable();
            $table->string('editedUserRole')->nullable();
            $table->string('editedDevice')->nullable();
            $table->timestamp('editedTime')->nullable();
            $table->string('status')->nullable();
        });
    }

    public function down()
    {
        Schema::table('omc', function (Blueprint $table) {
            $table->dropColumn(['userName', 'userId', 'userRole', 'editedUserName', 'editedUserId', 'editedUserRole', 'editedDevice', 'editedTime', 'status']);
        });
    }
};
