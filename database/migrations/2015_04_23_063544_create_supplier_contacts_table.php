<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('supplier_contacts', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';

			$table->increments('id');
			$table->timestamps();

            // Link many customer contacts to one address
            $table->integer('supplier_id')->unsigned();

            // Based on printflow v1

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 100);
            $table->string('mobile', 100);
            $table->string('email', 100);
            
            // TODO: delivery address

            $table->boolean('accounts_person');
            $table->boolean('primary_person');

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
		Schema::drop('supplier_contacts');
	}

}
