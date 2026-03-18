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
        Schema::create('complaint_service_parts_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('complaint_id'); 
            $table->unsignedBigInteger('part_id'); 
            $table->integer('quantity'); 
            $table->string('remark', 255)->nullable(); 
            $table->boolean('is_urgent')->default(false); 
            $table->timestamps(); 

            // Define foreign key constraints
            $table->foreign('complaint_id')->references('id')->on('complaints')->onDelete('cascade');
            $table->foreign('part_id')->references('id')->on('item_parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_service_parts_details');
    }
};
