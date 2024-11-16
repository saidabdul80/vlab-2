<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminCourseResources extends Controller
{
    public function index(){
        return view('admin/CourseResources');
    }
}
