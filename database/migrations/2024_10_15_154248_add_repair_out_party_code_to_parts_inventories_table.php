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
        Schema::table('parts_inventories', function (Blueprint $table) {
            $table->string('repair_out_party_code')->nullable()->after('repair_out_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parts_inventories', function (Blueprint $table) {
            $table->dropColumn('repair_out_party_code');
        });
    }
};
