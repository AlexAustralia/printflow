<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_addresses', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';

			$table->increments('id');
			$table->timestamps();

            $table->integer('customer_id')->unsigned();

            $table->string('name', 100);

            $table->string('address', 100);
            $table->string('city', 50);
            $table->string('state', 20);
            $table->string('postcode', 10);
            $table->string('country', 20);
            $table->text('notes');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customer_addresses');
	}

}
