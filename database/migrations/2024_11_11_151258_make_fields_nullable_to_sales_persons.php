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
            $table->date('date')->nullable()->change();
            $table->time('time')->nullable()->change();
            $table->string('mobile_no')->nullable()->change();
            $table->string('partyname')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_persons', function (Blueprint $table) {
            $table->date('date')->nullable('false')->change();
            $table->time('time')->nullable('false')->change();
            $table->string('mobile_no')->nullable('false')->change();
            $table->string('partyname')->nullable('false')->change();
        });
    }
};
