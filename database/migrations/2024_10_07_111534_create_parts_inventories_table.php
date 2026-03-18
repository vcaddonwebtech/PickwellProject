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
        Schema::create('parts_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('in_engineer_id');
            $table->integer('vou_no');
            $table->date('date');
            $table->time('time');
            $table->unsignedBigInteger('in_party_id');
            $table->unsignedBigInteger('product_id');
            $table->string('card_no');
            $table->integer('quntity')->default(1);
            $table->text('remarks')->nullable();
            $table->date('repair_out_date')->nullable();
            $table->time('repair_out_time')->nullable();
            $table->unsignedBigInteger('repair_out_party_id')->nullable();
            $table->text('repair_out_remarks')->nullable();
            $table->date('expexted_required_date')->nullable();
            $table->date('repair_in_date')->nullable();
            $table->time('repair_in_time')->nullable();
            $table->text('repair_in_remarks')->nullable();
            $table->date('issue_date')->nullable();
            $table->time('issue_time')->nullable();
            $table->unsignedBigInteger('issue_engineer_id')->nullable();
            $table->text('issue_remarks')->nullable();
            $table->tinyInteger('repair_status')->default(1)->comment('1-Receive Parts, 2-Repair Out, 3-Repair In, 4-Issue to Party');
            $table->timestamps();

            $table->foreign('in_engineer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('in_party_id')->references('id')->on('parties')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('repair_out_party_id')->references('id')->on('parties')->onDelete('cascade');
            $table->foreign('issue_engineer_id')->references('id')->on('users')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parts_inventories');
    }
};
