<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Area;
use App\Models\Measurement;
use App\Models\MeasurementVariable;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MeasurementController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'area_id' => ['nullable', 'integer', 'exists:areas,id'],
            'measurement_variable_id' => ['nullable', 'integer', 'exists:measurement_variables,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'shift' => ['nullable', 'in:manana,tarde,noche'],
            'status' => ['nullable', 'in:recorded,out_of_range'],
        ]);

        $measurements = Measurement::query()
            ->with(['variable.area', 'variable.frequency', 'user'])
            ->when($filters['area_id'] ?? null, fn ($query, $areaId) => $query->whereHas(
                'variable',
                fn ($variableQuery) => $variableQuery->where('area_id', $areaId),
            ))
            ->when($filters['measurement_variable_id'] ?? null, fn ($query, $variableId) => $query->where(
                'measurement_variable_id',
                $variableId,
            ))
            ->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('measured_date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('measured_date', '<=', $date))
            ->when($filters['shift'] ?? null, fn ($query, $shift) => $query->where('shift', $shift))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest('measured_at')
            ->paginate(20)
            ->withQueryString();

        return view('measurements.index', [
            'measurements' => $measurements,
            'areas' => Area::query()->where('is_active', true)->orderBy('name')->get(),
            'variables' => MeasurementVariable::query()
                ->with('area', 'frequency')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('measurements.create', [
            'variables' => MeasurementVariable::query()
                ->with('area', 'frequency')
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'measurement_variable_id' => ['required', 'integer', 'exists:measurement_variables,id'],
            'measured_date' => ['required', 'date'],
            'measured_time' => ['required', 'date_format:H:i'],
            'shift' => ['nullable', 'in:manana,tarde,noche'],
            'value' => ['required', 'numeric'],
            'observation' => ['nullable', 'string', 'max:1000'],
        ]);

        $variable = MeasurementVariable::query()
            ->with('frequency', 'area')
            ->where('is_active', true)
            ->findOrFail($validated['measurement_variable_id']);

        if ($variable->frequency->requires_shift && empty($validated['shift'])) {
            return back()
                ->withErrors(['shift' => 'Esta variable requiere seleccionar un turno.'])
                ->withInput();
        }

        $measuredAt = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['measured_date'].' '.$validated['measured_time'],
        );
        $value = (float) $validated['value'];
        $status = $this->statusFor($variable, $value);
        $shift = $variable->frequency->requires_shift ? $validated['shift'] : null;

        $measurement = Measurement::create([
            'measurement_variable_id' => $variable->id,
            'user_id' => $request->user()->id,
            'measured_at' => $measuredAt,
            'measured_date' => $measuredAt->toDateString(),
            'shift' => $shift,
            'value' => $validated['value'],
            'observation' => $validated['observation'] ?? null,
            'status' => $status,
        ]);

        ActivityLog::record(
            'measurements',
            'created',
            "Se registro una medicion de {$variable->name} en {$variable->area->name}.",
            [
                'measurement_id' => $measurement->id,
                'variable_id' => $variable->id,
                'status' => $status,
            ],
        );

        return redirect()
            ->route('measurements.index')
            ->with('status', 'La medicion se registro correctamente.');
    }

    private function statusFor(MeasurementVariable $variable, float $value): string
    {
        $belowMinimum = $variable->min_value !== null && $value < (float) $variable->min_value;
        $aboveMaximum = $variable->max_value !== null && $value > (float) $variable->max_value;

        return $belowMinimum || $aboveMaximum ? 'out_of_range' : 'recorded';
    }
}
