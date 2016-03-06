<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtworkChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artwork_charges', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('quote_request_id');
            $table->text('description')->nullable();
            $table->integer('supplier_id');
            $table->float('hours')->nullable();
            $table->decimal('rate', 8, 2)->nullable();
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
        Schema::drop('artwork_charges');
    }
}
