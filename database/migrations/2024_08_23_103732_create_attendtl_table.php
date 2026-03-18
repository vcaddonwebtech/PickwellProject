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
        Schema::create('attendtl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('firm_id');            
            $table->unsignedBigInteger('engineer_id');            
            $table->unsignedBigInteger('year_id');            
            $table->date('in_date');
            $table->string('in_time');
            $table->date('out_date')->nullable();
            $table->string('out_time')->nullable();
            $table->string('ap')->default('P');
            $table->float('late_hrs');
            $table->float('earligoing_hrs')->nullable();
            $table->float('working_hrs')->nullable();
            $table->float('pdays')->default(1);
            $table->string('in_late');
            $table->string('in_long');
            $table->longText('in_address');
            $table->string('out_late')->nullable();;
            $table->string('out_long')->nullable();;
            $table->longText('out_address')->nullable();;
            $table->timestamps();

            // Define foreign key constraints if necessary
            $table->foreign('firm_id')->references('id')->on('firms');
            $table->foreign('engineer_id')->references('id')->on('users');
            $table->foreign('year_id')->references('id')->on('years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendtl');
    }
};
