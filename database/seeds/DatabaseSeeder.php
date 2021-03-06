<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		//$this->call('UserTableSeeder');
        //$this->command->info('User table seeded!');
		
        //$this->call('SupplierTableSeeder');
        //$this->command->info('Supplier table seeded!');

        //$this->call('CustomerTableSeeder');
        //$this->command->info('Customer table seeded!');

		$this->call('StatusTableSeeder');
		$this->command->info('Status table seeded!');
	}

}


