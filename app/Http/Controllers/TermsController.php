<?php

namespace App\Http\Controllers;

use App\Term;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class TermsController extends Controller
{
    public function get_terms()
    {
        $terms = Term::select('id', 'name', 'description')->get();
        $message = Session::get('message');

        return view('setup.terms', compact('terms', 'message'));
    }

    public function post_terms()
    {
        $input = Input::all();

        if ($input['submit'] == 'save') {
            if ($input['id'] > 0) {
                $terms = Term::find($input['id']);
                $terms->update($input);
            } else {
                $terms = new Term();
                $terms->create($input);
            }
        } else {
            if ($input['id'] > 0) Term::destroy($input['id']);
        }

        return redirect('terms')->with('message', 'Terms have been updated successfully');
    }
}
