<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $heroes = Hero::active()->ordered()->get();
        
        return view('index', compact('heroes'));
    }
}
