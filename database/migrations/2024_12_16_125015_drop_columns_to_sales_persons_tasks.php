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
            $table->dropColumn(['in_date_time', 'out_date_time','in_address', 'out_address']);
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
