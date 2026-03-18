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
        Schema::create('item_parts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // name varchar(255) NOT NULL
            $table->unsignedBigInteger('product_group_id'); // product_group_id int(11) NOT NULL
            $table->integer('tag')->default(2); // tag int (11) NOT NULL DEFAULT 2
            $table->string('hsn_code', 8); // hsn_code varchar(8) NOT NULL
            $table->float('gst', 8, 2); // gst float(8,2) NOT NULL
            $table->float('rate', 8, 2); 
            $table->timestamps();
            $table->foreign('product_group_id')
                  ->references('id')
                  ->on('product_groups')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_parts');
    }
};
