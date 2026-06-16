<?php

namespace App\Http\Controllers;

use App\Models\Inspeccion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InspeccionController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['fecha_desde', 'fecha_hasta', 'sector', 'estado']);

        $inspecciones = $this->filteredInspecciones($filters)->get();

        return view('inspecciones.index', compact('inspecciones', 'filters'));
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['fecha_desde', 'fecha_hasta', 'sector', 'estado']);
        $inspecciones = $this->filteredInspecciones($filters)->get();

        return response()->streamDownload(function () use ($inspecciones): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['fecha', 'sector', 'estado', 'observacion', 'cargado_por']);

            foreach ($inspecciones as $inspeccion) {
                fputcsv($output, [
                    $inspeccion->fecha,
                    $inspeccion->sector,
                    $inspeccion->estado,
                    $inspeccion->observacion,
                    $inspeccion->usuario?->name,
                ]);
            }

            fclose($output);
        }, 'inspecciones.csv', [
            'Content-Type' => 'text/csv',
        ]);
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

    /**
     * @param  array<string, string|null>  $filters
     */
    private function filteredInspecciones(array $filters)
    {
        return Inspeccion::with('usuario')
            ->when($filters['fecha_desde'] ?? null, fn ($query, $fecha) => $query->whereDate('fecha', '>=', $fecha))
            ->when($filters['fecha_hasta'] ?? null, fn ($query, $fecha) => $query->whereDate('fecha', '<=', $fecha))
            ->when($filters['sector'] ?? null, fn ($query, $sector) => $query->where('sector', 'like', "%{$sector}%"))
            ->when($filters['estado'] ?? null, fn ($query, $estado) => $query->where('estado', $estado))
            ->latest();
    }
}
