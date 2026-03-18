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
        Schema::table('sales_persons_tasks', function (Blueprint $table) {
            $table->dateTime('in_date_time')->nullable()->after('assign_user_id');
            $table->dateTime('out_date_time')->nullable()->after('assign_user_id');
            $table->string('in_address')->nullable()->after('assign_user_id');
            $table->string('out_address')->nullable()->after('assign_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_persons_tasks', function (Blueprint $table) {
            //
        });
    }
};
