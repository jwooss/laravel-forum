<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        flash('환영zxcz합니다');
        flash()->success('asdasdsa');

        return view('home');
    }
}
