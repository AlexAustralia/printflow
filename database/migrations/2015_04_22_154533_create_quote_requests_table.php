<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 * This table contains the information about quotes
	 * @return void
	 */
	public function up()
	{
		Schema::create('quote_requests', function(Blueprint $table)
		{
			$table->increments('id'); // quote number
			$table->timestamps();

            $table->integer('customer_id')->unsigned();
            $table->date('request_date');
            $table->date('expiry_date');
            $table->string('ref', 100);
            $table->string('title', 100);
            $table->text('summary');
            $table->text('terms');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('quote_requests');
	}

}
