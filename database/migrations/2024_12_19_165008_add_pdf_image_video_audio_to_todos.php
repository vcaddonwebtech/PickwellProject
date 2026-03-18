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
        Schema::table('todos', function (Blueprint $table) {
            $table->string('pdf')->nullable()->after('priority_id');
            $table->string('image')->nullable()->after('pdf');
            $table->string('video')->nullable()->after('image');
            $table->string('audio')->nullable()->after('video');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn('pdf');
            $table->dropColumn('image');
            $table->dropColumn('video');
            $table->dropColumn('audio');
        });
    }
};
