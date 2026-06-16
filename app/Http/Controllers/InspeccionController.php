<?php

namespace App\Http\Controllers;

use App\Models\Inspeccion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InspeccionController extends Controller
{
    public function index(): View
    {
        $inspecciones = Inspeccion::with('usuario')
            ->latest()
            ->get();

        return view('inspecciones.index', compact('inspecciones'));
    }

    public function create(): View
    {
        return view('inspecciones.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'sector' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'in:correcto,observado,critico'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ]);

        $data['user_id'] = Auth::id();

        Inspeccion::create($data);

        return redirect()
            ->route('inspecciones.index')
            ->with('status', 'Inspeccion guardada correctamente.');
    }

    public function edit(Inspeccion $inspeccion): View
    {
        return view('inspecciones.edit', compact('inspeccion'));
    }

    public function update(Request $request, Inspeccion $inspeccion): RedirectResponse
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'sector' => ['required', 'string', 'max:100'],
            'estado' => ['required', 'in:correcto,observado,critico'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ]);

        $inspeccion->update($data);

        return redirect()
            ->route('inspecciones.index')
            ->with('status', 'Inspeccion actualizada correctamente.');
    }

    public function destroy(Inspeccion $inspeccion): RedirectResponse
    {
        $inspeccion->delete();

        return redirect()
            ->route('inspecciones.index')
            ->with('status', 'Inspeccion eliminada correctamente.');
    }
}
