<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        return view('projects.discussion');
    }
}
