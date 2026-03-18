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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('pan_no');
            $table->string('address');
            $table->unsignedBigInteger('city_id'); // Foreign key to cities
            $table->unsignedBigInteger('state_id'); // Foreign key to states
            $table->string('pincode');
            $table->string('phone_no');
            $table->string('other_phone_no', 500);
            $table->string('gst_no');
            $table->unsignedBigInteger('contact_person_id'); // Foreign key to contact_persons
            $table->unsignedBigInteger('owner_id'); // Foreign key to owners
            $table->unsignedBigInteger('area_id'); // Foreign key to areas
            $table->unsignedBigInteger('firm_id'); // Foreign key to firms
            $table->timestamps(); // created_at and updated_at timestamps

            // Define foreign key constraints
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('contact_person_id')->references('id')->on('contact_persons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
