<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quote_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();

            $table->integer('quote_id')->unsigned();

            $table->integer('quantity');

            $table->decimal('buy_price', 8, 2);
            $table->decimal('buy_price_unit', 8, 2);
            $table->decimal('duty', 8, 2);
            $table->decimal('freight_cbm', 8, 2);
            $table->decimal('freight_cost', 8, 2);
            $table->decimal('total_buy_cost', 8, 2);

            $table->decimal('markup', 8, 4);
            $table->decimal('total_net', 8, 2);
            $table->decimal('gst', 8, 2);            
            $table->decimal('total_inc_gst', 8, 2);
            $table->decimal('unit_price_inc_gst', 8, 2);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('quote_items');
	}

}
