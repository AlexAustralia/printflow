<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';

			$table->increments('id');
			$table->timestamps();
            
            $table->string('customer_name', 50);

            // Based on Xero fields
            $table->string('contact', 50);

            $table->string('postal_attention', 50);
            $table->string('postal_street', 50);
            $table->string('postal_city', 20);
            $table->string('postal_state', 20);
            $table->string('postal_postcode', 10);
            $table->string('postal_country', 10);

            $table->string('physical_attention', 50);
            $table->string('physical_street', 50);
            $table->string('physical_city', 20);
            $table->string('physical_state', 20);
            $table->string('physical_postcode', 10);
            $table->string('physical_country', 10);

            $table->string('tel_country', 10);
            $table->string('tel_area', 10);
            $table->string('tel_number', 10);

            $table->string('fax_country', 10);
            $table->string('fax_area', 10);
            $table->string('fax_number', 10);

            $table->string('mobile_country', 10);
            $table->string('mobile_area', 10);
            $table->string('mobile_number', 10);

            $table->string('direct_country', 10);
            $table->string('direct_area', 10);
            $table->string('direct_number', 10);

            $table->string('skype_name', 50);
            $table->string('web_address', 50);

            // Additional fields taken from printflow v1
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
		Schema::dropIfExists('customers');
	}

}
