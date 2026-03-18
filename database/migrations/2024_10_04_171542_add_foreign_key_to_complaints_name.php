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
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
            $table->foreign('complaint_type_id')->references('id')->on('complaint_types')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('sales_entry_id')->references('id')->on('machine_sales_entries')->onDelete('cascade');
            $table->foreign('engineer_id')->references('id')->on('engineers')->onDelete('set null');
            $table->foreign('engineer_complaint_id')->references('id')->on('engineers')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['firm_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['year_id']);
            $table->dropForeign(['complaint_type_id']);
            $table->dropForeign(['sales_entry_id']);
            $table->dropForeign(['engineer_id']);
            $table->dropForeign(['engineer_complaint_id']);
            $table->dropForeign(['service_type_id']);
            $table->dropForeign(['status_id']);
        });
    }
};
