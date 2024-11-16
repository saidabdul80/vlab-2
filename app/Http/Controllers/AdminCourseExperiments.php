<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminCourseExperiments extends Controller
{
    public function index(){
        return view('admin/CourseExperiments');
    }
}
