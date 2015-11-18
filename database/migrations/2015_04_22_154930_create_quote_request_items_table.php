<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteRequestItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quote_request_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();

            $table->integer('quote_request_id')->unsigned();

            $table->integer('quantity');
            $table->string('description', 100);
            $table->decimal('price', 8, 2);
            $table->integer('gst')->unsigned();
            $table->decimal('total', 8, 2);
            $table->decimal('unit_price', 8, 2);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('quote_request_items');
	}

}
