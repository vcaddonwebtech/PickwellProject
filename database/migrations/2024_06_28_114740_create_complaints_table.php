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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('firm_id');
            $table->unsignedBigInteger('year_id');
            $table->unsignedBigInteger('party_id');
            $table->date('date');
            $table->time('time');
            $table->unsignedBigInteger('complaint_type_id');
            $table->unsignedBigInteger('sales_entry_id');
            $table->unsignedBigInteger('product_id');
            $table->string('remarks', 255);
            $table->string('image', 255);
            $table->unsignedBigInteger('engineer_id')->nullable();
            $table->date('engineer_assign_date')->nullable();
            $table->time('engineer_assign_time')->nullable();
            $table->time('engineer_in_time')->nullable();
            $table->time('engineer_out_time')->nullable();
            $table->date('engineer_in_date')->nullable();
            $table->date('engineer_out_date')->nullable();
            $table->unsignedBigInteger('engineer_complaint_id');
            $table->string('jointengg', 15)->nullable();
            $table->unsignedBigInteger('service_type_id');
            $table->unsignedBigInteger('status_id');
            $table->string('complaint_id', 255)->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
            $table->foreign('complaint_type_id')->references('id')->on('complaint_types')->onDelete('cascade');
            $table->foreign('sales_entry_id')->references('id')->on('machine_sales_entries')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('complaints');
    }
};
