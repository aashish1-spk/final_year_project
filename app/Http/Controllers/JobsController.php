<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobsController extends Controller
{
    //this method will show jobs page
   public function index() {
    return view('front.jobs');
   }
}
