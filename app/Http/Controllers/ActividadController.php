<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\View\View;

class ActividadController extends Controller
{
    public function index(): View
    {
        $actividades = Actividad::with('usuario')
            ->latest()
            ->limit(100)
            ->get();

        return view('actividad.index', compact('actividades'));
    }
}
