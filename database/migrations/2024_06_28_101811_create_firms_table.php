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
        Schema::create('firms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // name varchar(255) NOT NULL
            $table->string('sh_code'); // sh_code varchar(255) NOT NULL
            $table->string('address'); // address varchar(255) NOT NULL
            $table->unsignedBigInteger('city_id'); // city_id bigint unsigned NOT NULL
            $table->unsignedBigInteger('state_id'); // state_id bigint unsigned NOT NULL
            $table->string('pincode', 10); // pincode varchar(10) NOT NULL
            $table->bigInteger('phone_no'); // phone_no bigint NOT NULL (without auto_increment or primary key)
            $table->string('gst_no', 25); // gst_no varchar(25) NOT NULL
            $table->string('pan_no', 25); // pan_no varchar(25) NOT NULL
            $table->string('reg_no', 25); // reg_no varchar(25) NOT NULL
            $table->string('bank_name')->nullable(); // bank_name varchar(255) NULL
            $table->string('branch_name')->nullable(); // branch_name varchar(255) NULL
            $table->string('bank_account_no')->nullable(); // bank_account_no varchar(255) NULL
            $table->string('bank_ifsc_code')->nullable(); // bank_ifsc_code varchar(255) NULL
            $table->timestamps(); // created_at and updated_at timestamps

            // Define foreign key constraints if necessary
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firms');
    }
};
