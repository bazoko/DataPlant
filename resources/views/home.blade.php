@extends('layouts.app')

@section('title', 'Dashboard | DataPlant')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Analitica operativa</p>
            <h1>Dashboard de mediciones</h1>
            <p>Observa la tendencia de una variable y detecta rapidamente valores fuera de rango.</p>
        </div>
        <div class="actions">
            @can('measurements.view')
                <a class="button secondary" href="{{ route('measurements.index') }}">Ver historial</a>
            @endcan
            @can('measurements.create')
                <a class="button" href="{{ route('measurements.create') }}">Registrar medicion</a>
            @endcan
        </div>
    </div>

    <section class="panel">
        <h2>Seleccion de analisis</h2>
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="filters">
                <div class="field">
                    <label for="area_id">Area</label>
                    <select id="area_id" name="area_id">
                        <option value="">Todas las areas</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" @selected((string) ($filters['area_id'] ?? '') === (string) $area->id)>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="measurement_variable_id">Variable</label>
                    <select id="measurement_variable_id" name="measurement_variable_id">
                        @forelse ($variables as $variable)
                            <option value="{{ $variable->id }}" @selected((string) ($filters['measurement_variable_id'] ?? '') === (string) $variable->id)>
                                {{ $variable->name }} ({{ $variable->unit }})
                            </option>
                        @empty
                            <option value="">No hay variables activas</option>
                        @endforelse
                    </select>
                </div>

                <div class="field">
                    <label for="date_from">Desde</label>
                    <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}">
                </div>

                <div class="field">
                    <label for="date_to">Hasta</label>
                    <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}">
                </div>
            </div>

            <div class="actions" style="margin-top: 16px;">
                <button class="button" type="submit">Actualizar dashboard</button>
                <a class="button secondary" href="{{ route('dashboard') }}">Restablecer</a>
            </div>
        </form>
    </section>

    <section class="grid kpi-grid">
        <div class="metric">
            <strong>{{ $latestMeasurement ? number_format((float) $latestMeasurement->value, 2) : 'Sin datos' }}</strong>
            <span>Ultimo valor{{ $selectedVariable ? ' ('.$selectedVariable->unit.')' : '' }}</span>
        </div>
        <div class="metric">
            <strong>{{ $stats['average'] !== null ? number_format((float) $stats['average'], 2) : 'Sin datos' }}</strong>
            <span>Promedio{{ $selectedVariable ? ' ('.$selectedVariable->unit.')' : '' }}</span>
        </div>
        <div class="metric">
            <strong>{{ $stats['minimum'] !== null ? number_format((float) $stats['minimum'], 2) : 'Sin datos' }}</strong>
            <span>Minimo{{ $selectedVariable ? ' ('.$selectedVariable->unit.')' : '' }}</span>
        </div>
        <div class="metric">
            <strong>{{ $stats['maximum'] !== null ? number_format((float) $stats['maximum'], 2) : 'Sin datos' }}</strong>
            <span>Maximo{{ $selectedVariable ? ' ('.$selectedVariable->unit.')' : '' }}</span>
        </div>
        <div class="metric">
            <strong>{{ $stats['count'] }}</strong>
            <span>Registros del periodo</span>
        </div>
        <div class="metric">
            <strong>{{ $stats['out_of_range'] }}</strong>
            <span>Fuera de rango</span>
        </div>
    </section>

    <section class="panel">
        <div class="chart-heading">
            <div>
                <h2>{{ $selectedVariable?->name ?: 'Tendencia de mediciones' }}</h2>
                <p>{{ $selectedVariable?->area?->name ?: 'Sin area seleccionada' }} · {{ $filters['date_from'] }} a {{ $filters['date_to'] }}</p>
            </div>
            @if ($selectedVariable)
                <span class="badge">Unidad: {{ $selectedVariable->unit }}</span>
            @endif
        </div>

        @if ($chartMeasurements->isNotEmpty())
            @php
                $chartWidth = 960;
                $chartHeight = 320;
                $chartLeft = 54;
                $chartRight = 22;
                $chartTop = 20;
                $chartBottom = 42;
                $plotWidth = $chartWidth - $chartLeft - $chartRight;
                $plotHeight = $chartHeight - $chartTop - $chartBottom;
                $values = $chartMeasurements->map(fn ($measurement): float => (float) $measurement->value)->values();
                $chartMin = (float) $values->min();
                $chartMax = (float) $values->max();
                if ($chartMin === $chartMax) {
                    $chartMin -= 1;
                    $chartMax += 1;
                }
                $chartRange = $chartMax - $chartMin;
                $chartPoints = $chartMeasurements->values()->map(function ($measurement, int $index) use ($chartMeasurements, $chartLeft, $plotWidth, $chartTop, $plotHeight, $chartMin, $chartRange): array {
                    $count = $chartMeasurements->count();
                    $x = $count === 1 ? $chartLeft + ($plotWidth / 2) : $chartLeft + (($index / ($count - 1)) * $plotWidth);
                    $y = $chartTop + ($plotHeight - ((((float) $measurement->value - $chartMin) / $chartRange) * $plotHeight));

                    return ['x' => $x, 'y' => $y, 'measurement' => $measurement];
                });
                $pointString = $chartPoints->map(fn (array $point): string => number_format($point['x'], 2, '.', '').','.number_format($point['y'], 2, '.', ''))->implode(' ');
            @endphp

            <div class="chart-wrap">
                <svg class="chart" viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" role="img" aria-label="Tendencia de {{ $selectedVariable?->name }}">
                    <line class="chart-gridline" x1="{{ $chartLeft }}" y1="{{ $chartTop }}" x2="{{ $chartWidth - $chartRight }}" y2="{{ $chartTop }}"></line>
                    <line class="chart-gridline" x1="{{ $chartLeft }}" y1="{{ $chartTop + ($plotHeight / 2) }}" x2="{{ $chartWidth - $chartRight }}" y2="{{ $chartTop + ($plotHeight / 2) }}"></line>
                    <line class="chart-gridline" x1="{{ $chartLeft }}" y1="{{ $chartTop + $plotHeight }}" x2="{{ $chartWidth - $chartRight }}" y2="{{ $chartTop + $plotHeight }}"></line>
                    <text class="chart-axis-label" x="8" y="{{ $chartTop + 4 }}">{{ number_format($chartMax, 2) }}</text>
                    <text class="chart-axis-label" x="8" y="{{ $chartTop + ($plotHeight / 2) + 4 }}">{{ number_format(($chartMax + $chartMin) / 2, 2) }}</text>
                    <text class="chart-axis-label" x="8" y="{{ $chartTop + $plotHeight + 4 }}">{{ number_format($chartMin, 2) }}</text>
                    <polyline class="chart-line" points="{{ $pointString }}"></polyline>
                    @foreach ($chartPoints as $point)
                        <circle class="chart-point" cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="5">
                            <title>{{ $point['measurement']->measured_at->format('Y-m-d H:i') }}: {{ $point['measurement']->value }} {{ $selectedVariable?->unit }}</title>
                        </circle>
                    @endforeach
                    <text class="chart-axis-label" x="{{ $chartLeft }}" y="{{ $chartHeight - 10 }}">{{ $chartMeasurements->first()->measured_at->format('d/m H:i') }}</text>
                    <text class="chart-axis-label" x="{{ $chartWidth - $chartRight }}" y="{{ $chartHeight - 10 }}" text-anchor="end">{{ $chartMeasurements->last()->measured_at->format('d/m H:i') }}</text>
                </svg>
            </div>
        @else
            <p class="muted">No hay datos para la variable y el periodo seleccionados.</p>
        @endif
    </section>

    <section class="panel">
        <h2>Ultimos registros del periodo</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha y hora</th>
                    <th>Valor</th>
                    <th>Turno</th>
                    <th>Estado</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestMeasurements as $measurement)
                    <tr>
                        <td>{{ $measurement->measured_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $measurement->value }} {{ $selectedVariable?->unit }}</td>
                        <td>{{ $measurement->shift ? ucfirst($measurement->shift) : 'No aplica' }}</td>
                        <td>
                            <span class="badge {{ $measurement->status === 'out_of_range' ? 'danger' : '' }}">
                                {{ $measurement->status === 'out_of_range' ? 'Fuera de rango' : 'Registrada' }}
                            </span>
                        </td>
                        <td>{{ $measurement->user?->name ?: 'Sistema' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">No hay registros para los filtros seleccionados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
