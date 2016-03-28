<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreightItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freight_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('freight_id')->unsigned();
            $table->integer('qri_id')->unsigned();
            $table->integer('supplier_id');
            $table->decimal('cbm', 8, 2)->nullable();
            $table->decimal('cbm_rate', 8, 2)->nullable();
            $table->integer('number_items')->nullable();
            $table->decimal('fixed_cost', 8, 2)->nullable();
            $table->decimal('total_cost', 8, 2);
            $table->decimal('markup', 8, 2);
            $table->decimal('total', 8, 2);

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
        Schema::drop('freight_items');
    }
}
