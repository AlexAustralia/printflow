<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;

class ProjectsController extends Controller
{
    public function brief()
    {
        return view('projects.brief');
    }

    public function checklist()
    {
        return view('projects.checklist');
    }

    public function discussion()
    {
        $messages = Message::all();

        return view('projects.discussion', compact('messages'));
    }

    public function saveDiscussion()
    {
        $input = Input::all();

        $message = new Message();

        $message->body = $input['message'];
        $message->user_id = Auth::user()->id;

        // Storing image file, if it is attached
        if (Input::hasFile('image'))
        {
            $file = Input::file('image');

            if ($file->isValid()) {
                $filename = rand(111111, 999999) . '-' . $file->getClientOriginalName();
                $destination_path = 'uploads/projects/';

                $file->move($destination_path, $filename);

                $message->attachment = $filename;

                // Make a thumbnail picture
                $thumbnail = Image::make($destination_path.'/'.$filename);
                $thumbnail->resize(55, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $thumbnail->save('uploads/projects/thumbnails/'.$filename);
            }
        }

        $message->save();

        return redirect('/projects/discussion');
    }
}
