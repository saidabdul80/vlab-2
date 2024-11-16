<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminViewCourses extends Controller
{
    public function index(){
        return view('admin/viewcourses');
    }
}
