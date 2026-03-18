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
        Schema::table('sales_persons_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('todo_id')->nullable()->change();
            $table->date('date')->nullable()->change();
            $table->time('time')->nullable()->change();
            $table->text('comment_first')->nullable()->change();
            $table->text('comment_second')->nullable()->change();
            $table->integer('priority_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_persons_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('todo_id')->nullable('false')->change();
            $table->date('date')->nullable('false')->change();
            $table->time('time')->nullable('false')->change();
            $table->text('comment_first')->nullable('false')->change();
            $table->text('comment_second')->nullable('false')->change();
            $table->integer('priority_id')->nullable('false')->change();
        });
    }
};
