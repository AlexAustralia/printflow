<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');

            $table->string('name');
            $table->text('description');
            $table->integer('supplier_id');
            $table->integer('length');
            $table->integer('height');
            $table->integer('width');
            $table->integer('diameter');
            $table->string('material');
            $table->string('print_options');
            $table->integer('minimum_order_quantity');
            $table->tinyInteger('sample_available');
            $table->integer('production_lead_time');
            $table->decimal('unit_price_from', 8, 2);
            $table->decimal('unit_price_to', 8, 2);
            $table->string('product_image')->nullable();

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
        Schema::drop('products');
    }
}
