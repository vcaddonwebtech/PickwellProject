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
        Schema::create('sales_persons_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('todo_id');
            $table->date('date');
            $table->time('time');
            $table->text('comment_first');
            $table->text('comment_second');
            $table->integer('priority_id');
            $table->foreign('todo_id')->references('id')->on('sales_persons')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_persons_tasks');
    }
};
