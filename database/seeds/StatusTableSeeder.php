<?php

use Illuminate\Database\Seeder;
use App\Status as Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'value' => 'Quote In'
        ]);
        Status::create([
            'value' => 'Supplier Quote'
        ]);
        Status::create([
            'value' => 'Quote Out'
        ]);
        Status::create([
            'value' => 'New Job'
        ]);
        Status::create([
            'value' => 'Production'
        ]);
        Status::create([
            'value' => 'Incoming'
        ]);
        Status::create([
            'value' => 'Delivery'
        ]);
        Status::create([
            'value' => 'Invoice'
        ]);
        Status::create([
            'value' => 'Complete'
        ]);
        Status::create([
            'value' => 'Cancel'
        ]);
    }

}
