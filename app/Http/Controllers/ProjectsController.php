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
    /**
     * Display Project Brief page
     *
     * @return \Illuminate\View\View
     */
    public function brief()
    {
        return view('projects.brief');
    }

    /**
     * Display Project Checklist page
     *
     * @return \Illuminate\View\View
     */
    public function checklist()
    {
        return view('projects.checklist');
    }

    /**
     * Display Project Discussion page
     *
     * @return \Illuminate\View\View
     */
    public function discussion()
    {
        $messages = Message::all();

        return view('projects.discussion', compact('messages'));
    }

    /**
     * Save message to Discussion page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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

    /**
     * Remove message from Discussion
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteMessage()
    {
        $input = Input::all();

        $message = Message::find($input['delete']);

        if (!is_null($message->attachment)) {

            $image = 'uploads/projects/' . $message->attachment;
            $thumbnail = 'uploads/projects/thumbnails/' . $message->attachment;

            if (file_exists($image)) unlink($image);
            if (file_exists($thumbnail)) unlink($thumbnail);
        }

        $message->delete();

        return redirect('/projects/discussion');
    }
}
