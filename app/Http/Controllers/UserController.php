<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        $message = Session::get('message');

        return view('users.index', compact('message', 'users'));
    }

    public function create()
    {
        return view('users.edit');
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('users.edit')->with('user', $user);
    }

    public function save(Request $request)
    {
        $input = Input::all();

        $this->validate($request, [
            'name' => 'required'
        ]);

        if($input['id'] == 0)
        {
            // New User
            $this->validate($request, [
                'email' => 'required|unique:users',
                'password' => 'required'
            ]);

            $user = new User();
        }
        else
        {
            // Store edited user
            $this->validate($request, [
                'email' => 'required'
            ]);

            $user = User::find($input['id']);

        }

        $user->name = $input['name'];
        $user->email = $input['email'];
        if(isset($input['password'])){
            $user->password = Hash::make($input['password']);
        }
        $user->admin = isset($input['admin']) ? 1 : 0;

        $user->save();

        return redirect('users')->with('message', 'User has been saved successfully');
    }

    public function delete()
    {
        $input = Input::all();

        $user = User::find($input['id']);
        $user->delete();

        $message = 'User ' . $user->name . ' has been deleted successfully';

        return redirect('users')->with('message', $message);
    }
}
