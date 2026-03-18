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
        Schema::create('sales_persons', function (Blueprint $table) {
            $table->id();
            $table->string('tag');
            $table->unsignedBigInteger('firm_id');            
            $table->unsignedBigInteger('year_id'); 
            $table->unsignedBigInteger('area_id');           
            $table->unsignedBigInteger('product_id');           
            $table->unsignedBigInteger('lead_stage_id');
            $table->unsignedBigInteger('sale_user_id');                    
            $table->unsignedBigInteger('sale_assign_user_id');                    
            $table->date('date');
            $table->time('time');
            $table->string('mobile_no');
            $table->string('partyname');            
            $table->longText('address')->nullable();
            $table->longText('location_Address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('logitude')->nullable();
            $table->text('remarks')->nullable();
            $table->date('next_reminder_date')->nullable();
            $table->time('next_reminder_time')->nullable();
            $table->timestamps();

            // Define foreign key constraints if necessary
            $table->foreign('firm_id')->references('id')->on('firms');
            $table->foreign('year_id')->references('id')->on('years');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('lead_stage_id')->references('id')->on('lead_stages');
            $table->foreign('sale_user_id')->references('id')->on('users');
            $table->foreign('sale_assign_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_persons');
    }
};
