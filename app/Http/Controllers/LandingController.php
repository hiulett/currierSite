<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Handle the incoming request for the corporate website.
     */
    public function index()
    {
        return view('landing.index');
    }
}
