<?php

use Illuminate\Database\Seeder;
use App\Customer;
use App\CustomerContact;
  
class CustomerTableSeeder extends Seeder {
  
    public function run() {
  
        require_once("customer.php");

        foreach($customer as $c){
            $customer = Customer::create(['customer_name' => $c['CustomerName']]);
            
            if ($c['Contact'] != "") {
                $contact = CustomerContact::create(['first_name' => $c['Contact'],
                                                'mobile' => $c['MobileNumber'],
                                                'email' => $c['Email'],
                                                'phone' => $c['TelephoneNumber'],
                                               ]);

                // todo: also deal with Contact2 info
                // todo: also get all records from existing customer_contacts tables

                $customer->customer_contacts()->save($contact);
            }
            
        }
    }

}

