<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Measurement;
use App\Models\MeasurementVariable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'area_id' => ['nullable', 'integer', 'exists:areas,id'],
            'measurement_variable_id' => ['nullable', 'integer', 'exists:measurement_variables,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $latestDate = Measurement::query()->max('measured_date');
        $defaultTo = $latestDate ? Carbon::parse($latestDate) : now();
        $defaultFrom = $defaultTo->copy()->subDays(6);
        $dateFrom = $filters['date_from'] ?? $defaultFrom->toDateString();
        $dateTo = $filters['date_to'] ?? (($filters['date_from'] ?? null) ? now()->toDateString() : $defaultTo->toDateString());

        $variablesQuery = MeasurementVariable::query()
            ->with('area', 'frequency')
            ->where('is_active', true)
            ->when($filters['area_id'] ?? null, fn ($query, $areaId) => $query->where('area_id', $areaId))
            ->orderBy('name');

        $variables = $variablesQuery->get();
        $selectedVariable = $variables->firstWhere('id', (int) ($filters['measurement_variable_id'] ?? 0))
            ?? $variables->first();

        $latestMeasurements = collect();
        $chartMeasurements = collect();
        $latestMeasurement = null;
        $stats = [
            'count' => 0,
            'average' => null,
            'minimum' => null,
            'maximum' => null,
            'out_of_range' => 0,
        ];

        if ($selectedVariable) {
            $baseQuery = Measurement::query()
                ->where('measurement_variable_id', $selectedVariable->id)
                ->whereBetween('measured_date', [$dateFrom, $dateTo]);

            $aggregate = (clone $baseQuery)
                ->selectRaw('COUNT(*) as count, AVG(value) as average, MIN(value) as minimum, MAX(value) as maximum')
                ->first();

            $stats = [
                'count' => (int) ($aggregate?->count ?? 0),
                'average' => $aggregate?->average,
                'minimum' => $aggregate?->minimum,
                'maximum' => $aggregate?->maximum,
                'out_of_range' => (clone $baseQuery)->where('status', 'out_of_range')->count(),
            ];

            $latestMeasurement = (clone $baseQuery)
                ->with(['variable.area', 'variable.frequency', 'user'])
                ->latest('measured_at')
                ->first();

            $latestMeasurements = (clone $baseQuery)
                ->with(['variable.area', 'variable.frequency', 'user'])
                ->latest('measured_at')
                ->limit(8)
                ->get();

            $chartMeasurements = (clone $baseQuery)
                ->with('user')
                ->oldest('measured_at')
                ->get();
        }

        return view('home', [
            'areaCount' => Area::query()->where('is_active', true)->count(),
            'variableCount' => MeasurementVariable::query()->where('is_active', true)->count(),
            'measurementCount' => Measurement::count(),
            'latestMeasurements' => $latestMeasurements,
            'chartMeasurements' => $chartMeasurements,
            'latestMeasurement' => $latestMeasurement,
            'selectedVariable' => $selectedVariable,
            'stats' => $stats,
            'areas' => Area::query()->where('is_active', true)->orderBy('name')->get(),
            'variables' => $variables,
            'filters' => [
                'area_id' => $filters['area_id'] ?? '',
                'measurement_variable_id' => $selectedVariable?->id ?? '',
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ]);
    }
}
