<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MoodleService;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function redirectMoodle(){
        if (!auth()->guard('admin')->user()->username){
            session()->flash("Unathrozied user");
            redirect()->back();

        }
        $moodlUrl = \App\Services\MoodleService::getAdminLoginURL(auth()->guard('admin')->user());

        return redirect()->to($moodlUrl);
    }
}
