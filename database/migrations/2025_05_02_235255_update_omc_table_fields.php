<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('omc', function (Blueprint $table) {
            
            $table->dropColumn(['first_name', 'middle_name', 'last_name']);
            $table->string('omcName')->after('id');
    
        });
    }

    public function down(): void
    {
        Schema::table('omc', function (Blueprint $table) {
            $table->dropColumn([
                'omcName'
            ]);
        
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
        });
    }


};
