<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration {

	/**
	 * Run the migrations.
	 * This table contains the information about accepted quotes = jobs
	 * @return void
	 */
	public function up()
	{
		Schema::create('quotes', function(Blueprint $table)
		{
			$table->increments('id'); // job number
			$table->timestamps();

            $table->integer('quote_request_id')->unsigned();
            $table->integer('supplier_id')->unsigned();

            $table->decimal('price', 8, 2);
            $table->decimal('gst', 8, 2);
            $table->decimal('net_cost', 8, 2);
            $table->decimal('net_sell', 8, 2);
            $table->decimal('markup', 8, 2);

		});
		//
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
        Schema::drop('quotes');
	}

}
