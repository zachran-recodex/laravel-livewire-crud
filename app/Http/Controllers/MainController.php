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

    public function charter()
    {
        $services = Service::active()->ordered()->get();
        $fleets = Fleet::active()->ordered()->get();
        
        return view('charter', compact('services', 'fleets'));
    }

    public function fleet()
    {
        $fleets = Fleet::active()->ordered()->get();
        
        return view('fleet', compact('fleets'));
    }

    public function services()
    {
        $services = Service::active()->ordered()->get();
        
        return view('services', compact('services'));
    }

    public function about()
    {
        return view('about');
    }

    public function quote()
    {
        $services = Service::active()->ordered()->get();
        
        return view('quote', compact('services'));
    }

    public function serviceDetail(Service $service)
    {
        // Check if service is active
        if (!$service->is_active) {
            abort(404);
        }

        $relatedServices = Service::active()
            ->where('id', '!=', $service->id)
            ->ordered()
            ->take(3)
            ->get();
        
        return view('service-detail', compact('service', 'relatedServices'));
    }

    public function fleetDetail(Fleet $fleet)
    {
        // Check if fleet is active
        if (!$fleet->is_active) {
            abort(404);
        }

        $relatedFleets = Fleet::active()
            ->where('id', '!=', $fleet->id)
            ->where('category', $fleet->category)
            ->ordered()
            ->take(3)
            ->get();

        // If not enough same category fleets, get different category fleets
        if ($relatedFleets->count() < 3) {
            $additionalFleets = Fleet::active()
                ->where('id', '!=', $fleet->id)
                ->whereNotIn('id', $relatedFleets->pluck('id'))
                ->ordered()
                ->take(3 - $relatedFleets->count())
                ->get();
            
            $relatedFleets = $relatedFleets->merge($additionalFleets);
        }
        
        return view('fleet-detail', compact('fleet', 'relatedFleets'));
    }
}
