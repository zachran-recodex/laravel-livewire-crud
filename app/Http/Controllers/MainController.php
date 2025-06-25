<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use App\Models\Service;
use App\Models\Fleet;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $heroes = Hero::active()->ordered()->get();
        $services = Service::active()->ordered()->get();
        $fleets = Fleet::active()->ordered()->take(3)->get();

        return view('index', compact('heroes', 'services', 'fleets'));
    }
}
