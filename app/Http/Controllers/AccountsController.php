<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountsController extends Controller {

    public function login(){
    }

	public function suppliers()
	{
        $term = \Input::get('term');
        $suppliers = Supplier::where('supplier_name', 'like', "%$term%")
                            ->get()
                            ->lists('supplier_name', 'id');

		return response()->json($suppliers);
	}

	public function customers()
	{
        $term = \Input::get('term');
        $customers = Customer::where('customer_name', 'like', "%$term%")
                            ->orderBy('customer_name', 'asc')
                            ->get();
        $json = [];
        foreach ($customers as $c){
            array_push($json, array('value' => $c->id, 'label' => $c->customer_name));
        }

		return response()->json($json);
	}

}
