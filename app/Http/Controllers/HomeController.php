<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        view('welcome');
    }

    public function about()
    {
        return view('about');
    }
}
