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
        Schema::table('sales_persons', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable()->change();
            $table->unsignedBigInteger('year_id')->nullable()->change();
            $table->unsignedBigInteger('area_id')->nullable()->change();
            $table->unsignedBigInteger('product_id')->nullable()->change();
            $table->unsignedBigInteger('lead_stage_id')->nullable()->change();
            $table->unsignedBigInteger('sale_user_id')->nullable()->change();
            $table->unsignedBigInteger('sale_assign_user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_persons', function (Blueprint $table) {
            $table->unsignedBigInteger('firm_id')->nullable(false)->change();
            $table->unsignedBigInteger('year_id')->nullable(false)->change();
            $table->unsignedBigInteger('area_id')->nullable(false)->change();
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->unsignedBigInteger('lead_stage_id')->nullable(false)->change();
            $table->unsignedBigInteger('sale_user_id')->nullable(false)->change();
            $table->unsignedBigInteger('sale_assign_user_id')->nullable(false)->change();
        });
    }
};
