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
            $table->string('contenor_no')->nullable()->after('service_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('machine_sales_entries', function (Blueprint $table) {
            $table->dropColumn('contenor_no');
        });
    }
};
