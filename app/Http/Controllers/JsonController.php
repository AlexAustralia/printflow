<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Supplier;
use App\Customer;
use Illuminate\Http\Request;

class JsonController extends Controller {

	public function suppliers()
	{
        $term = \Input::get('term');
        $suppliers = Supplier::where('supplier_name', 'like', "%$term%")
                            ->orderBy('supplier_name', 'asc')
                            ->get();
        $json = [];
        foreach ($suppliers as $s){
            array_push($json, array('value' => $s->id, 'label' => $s->supplier_name));
        }

		return response()->json($json);
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
