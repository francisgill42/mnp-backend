<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('legacy_code_sku');
            $table->string('product_title');
            $table->decimal('product_price', 10, 2)->default(0);
            $table->string('product_image')->nullable();
            $table->decimal('unit_in_case',10, 2)->nullable();
            $table->string('expiry_date');
            $table->string('unit_of_measurement')->nullable();
            $table->longtext('product_description')->nullable();
            $table->boolean('IsActive')->default(0);
            $table->integer('weight')->nullable();           
            $table->integer('product_category_id')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
