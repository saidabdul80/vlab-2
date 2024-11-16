<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminViewStudentsdashboard extends Controller
{
    public function index(){
        return view('admin/ViewStudentsdashboard');
    }
}
