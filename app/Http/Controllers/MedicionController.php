<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Medicion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MedicionController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['fecha_desde', 'fecha_hasta', 'turno']);

        $mediciones = $this->filteredMediciones($filters)->get();

        return view('mediciones.index', compact('mediciones', 'filters'));
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['fecha_desde', 'fecha_hasta', 'turno']);
        $mediciones = $this->filteredMediciones($filters)->get();

        return response()->streamDownload(function () use ($mediciones): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['fecha', 'turno', 'valor', 'observacion', 'cargado_por']);

            foreach ($mediciones as $medicion) {
                fputcsv($output, [
                    $medicion->fecha,
                    $medicion->turno,
                    $medicion->valor,
                    $medicion->observacion,
                    $medicion->usuario?->name,
                ]);
            }

            fclose($output);
        }, 'mediciones.csv', [
            'Content-Type' => 'text/csv',
        ]);
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

        $medicion = Medicion::create($data);

        Actividad::registrar(
            'mediciones',
            'crear',
            "Creo la medicion #{$medicion->id} del {$medicion->fecha}"
        );

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

        Actividad::registrar(
            'mediciones',
            'editar',
            "Edito la medicion #{$medicion->id}"
        );

        return redirect()
            ->route('mediciones.index')
            ->with('status', 'Medicion actualizada correctamente.');
    }

    public function destroy(Medicion $medicion): RedirectResponse
    {
        $id = $medicion->id;
        $medicion->delete();

        Actividad::registrar(
            'mediciones',
            'eliminar',
            "Elimino la medicion #{$id}"
        );

        return redirect()
            ->route('mediciones.index')
            ->with('status', 'Medicion eliminada correctamente.');
    }

    /**
     * @param  array<string, string|null>  $filters
     */
    private function filteredMediciones(array $filters)
    {
        return Medicion::with('usuario')
            ->when($filters['fecha_desde'] ?? null, fn ($query, $fecha) => $query->whereDate('fecha', '>=', $fecha))
            ->when($filters['fecha_hasta'] ?? null, fn ($query, $fecha) => $query->whereDate('fecha', '<=', $fecha))
            ->when($filters['turno'] ?? null, fn ($query, $turno) => $query->where('turno', $turno))
            ->latest();
    }
}
