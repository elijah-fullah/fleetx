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
    Schema::table('dealer', function (Blueprint $table) {
        // Remove $table->id(); because id column should already exist

        // Add columns only if they don't exist yet:
        if (!Schema::hasColumn('dealer', 'first_name')) {
            $table->string('first_name');
        }
        if (!Schema::hasColumn('dealer', 'middle_name')) {
            $table->string('middle_name')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'last_name')) {
            $table->string('last_name');
        }
        if (!Schema::hasColumn('dealer', 'licence_no')) {
            $table->string('licence_no')->unique();
        }
        if (!Schema::hasColumn('dealer', 'omc_id')) {
            $table->string('omc_id');
        }
        if (!Schema::hasColumn('dealer', 'email')) {
            $table->string('email')->unique();
        }
        if (!Schema::hasColumn('dealer', 'licence_exp')) {
            $table->string('licence_exp')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'phone')) {
            $table->string('phone')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'device')) {
            $table->string('device')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'is_online')) {
            $table->boolean('is_online')->default(false);
        }
        if (!Schema::hasColumn('dealer', 'last_login')) {
            $table->timestamp('last_login')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'userName')) {
            $table->string('userName')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'userId')) {
            $table->unsignedBigInteger('userId')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'userRole')) {
            $table->string('userRole')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'editedUserName')) {
            $table->string('editedUserName')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'editedUserId')) {
            $table->unsignedBigInteger('editedUserId')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'editedUserRole')) {
            $table->string('editedUserRole')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'editedDevice')) {
            $table->string('editedDevice')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'editedTime')) {
            $table->timestamp('editedTime')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'status')) {
            $table->string('status')->nullable();
        }
        if (!Schema::hasColumn('dealer', 'remember_token')) {
            $table->rememberToken();
        }
        if (!Schema::hasColumn('dealer', 'created_at') && !Schema::hasColumn('dealer', 'updated_at')) {
            $table->timestamps();
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer');
    }
};
