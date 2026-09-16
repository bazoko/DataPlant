<?php

namespace App\Http\Controllers;

use App\Models\Inspeccion;
use App\Models\Medicion;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $totalMediciones = Medicion::count();
        $totalInspecciones = Inspeccion::count();
        $ultimasMediciones = Medicion::with('usuario')->latest()->limit(5)->get();
        $ultimasInspecciones = Inspeccion::with('usuario')->latest()->limit(5)->get();

        return view('home', compact(
            'totalMediciones',
            'totalInspecciones',
            'ultimasMediciones',
            'ultimasInspecciones'
        ));
    }
}
