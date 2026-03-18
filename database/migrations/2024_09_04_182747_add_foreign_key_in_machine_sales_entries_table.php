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
        Schema::table('machine_sales_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('mic_fitting_engineer_id')->after('is_active');
            $table->unsignedBigInteger('delivery_engineer_id')->after('mic_fitting_engineer_id');

            $table->foreign('mic_fitting_engineer_id')->references('id')->on('users');
            $table->foreign('delivery_engineer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('machine_sales_entries', function (Blueprint $table) {
            //
        });
    }
};
