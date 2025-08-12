<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingpageController extends Controller
{
   
    public function index()
    {
        return view('landingpage'); // Make sure 'landingpage.blade.php' exists in the resources/views directory
    }
}
