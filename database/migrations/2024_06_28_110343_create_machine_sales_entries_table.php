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
            Schema::create('machine_sales_entries', function (Blueprint $table) {
                $table->id(); // Primary key
                $table->unsignedBigInteger('firm_id'); // Foreign key to firms
                $table->unsignedBigInteger('year_id'); // Foreign key to years
                $table->integer('bill_no');
                $table->date('date');
                $table->unsignedBigInteger('party_id'); // Foreign key to parties
                $table->unsignedBigInteger('product_id'); // Foreign key to products
                $table->string('serial_no', 25)->nullable();
                $table->string('mc_no', 25); // Machine number
                $table->date('install_date');
                $table->date('service_expiry_date');
                $table->boolean('free_service')->default(0); // 0 - false, 1 - true
                $table->string('order_no', 25)->nullable();
                $table->string('remarks', 255)->nullable();
                $table->unsignedBigInteger('service_type_id'); // Foreign key to service_types
                $table->string('image', 255)->nullable();
                $table->string('image1', 255)->nullable();
                $table->string('image2', 255)->nullable();
                $table->string('image3', 255)->nullable();
                $table->string('lat', 255)->nullable();
                $table->string('long', 255)->nullable();
                $table->string('map_url', 255)->nullable();
                $table->integer('tag')->default(1);
                $table->boolean('is_active')->default(1);
                $table->unsignedBigInteger('mic_fitting_engineer_id'); // Foreign key to engineers
                $table->unsignedBigInteger('delivery_engineer_id'); // Foreign key to engineers
                $table->timestamps();

                // Define foreign key constraints
                $table->foreign('firm_id')->references('id')->on('firms')->onDelete('cascade');
                $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
                // $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');
                $table->foreign('mic_fitting_engineer_id')->references('id')->on('engineers')->onDelete('cascade');
                $table->foreign('delivery_engineer_id')->references('id')->on('engineers')->onDelete('cascade');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('machine_sales_entries');
        }
    };
