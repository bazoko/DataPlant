<?php

namespace App\Http\Controllers;

use App\Models\Medicion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MedicionController extends Controller
{
    public function index(): View
    {
        $mediciones = Medicion::with('usuario')
            ->latest()
            ->get();

        return view('mediciones.index', compact('mediciones'));
    }

    public function create(): View
    {
        return view('mediciones.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'turno' => ['required', 'in:manana,tarde,noche'],
            'valor' => ['required', 'numeric'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ]);

        $data['user_id'] = Auth::id();

        Medicion::create($data);

        return redirect()
            ->route('mediciones.index')
            ->with('status', 'Medicion guardada correctamente.');
    }

    public function edit(Medicion $medicion): View
    {
        return view('mediciones.edit', compact('medicion'));
    }

    public function update(Request $request, Medicion $medicion): RedirectResponse
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'turno' => ['required', 'in:manana,tarde,noche'],
            'valor' => ['required', 'numeric'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ]);

        $medicion->update($data);

        return redirect()
            ->route('mediciones.index')
            ->with('status', 'Medicion actualizada correctamente.');
    }

    public function destroy(Medicion $medicion): RedirectResponse
    {
        $medicion->delete();

        return redirect()
            ->route('mediciones.index')
            ->with('status', 'Medicion eliminada correctamente.');
    }
}
