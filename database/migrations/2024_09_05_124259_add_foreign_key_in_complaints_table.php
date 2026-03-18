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
        Schema::table('complaints', function (Blueprint $table) {
            $table->unsignedBigInteger('engineer_id')->nullable()->after('audio');
            $table->unsignedBigInteger('engineer_complaint_id')->after('engineer_id');

            $table->foreign('engineer_id')->references('id')->on('users');
            $table->foreign('engineer_complaint_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // 
        });
    }
};
