<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
            $table->string('delivery_date');
            $table->integer('qr_id')->unsigned();
            $table->text('input');
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
        Schema::drop('delivery_histories');
    }
}
