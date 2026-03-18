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
            $table->date('closed_date')->after('favorite')->nullable();
            $table->integer('closed_by')->after('favorite')->nullable();
            $table->integer('status_id')->after('favorite')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_persons', function (Blueprint $table) {
            //
        });
    }
};
