@extends('layouts.app')

@section('title', 'Mediciones | DataPlant')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Operacion</p>
            <h1>Historial de mediciones</h1>
            <p>Consulta los datos registrados por area, variable, fecha y turno.</p>
        </div>
        @can('measurements.create')
            <a class="button" href="{{ route('measurements.create') }}">Registrar medicion</a>
        @endcan
    </div>

    <section class="panel">
        <h2>Filtros</h2>
        <form method="GET" action="{{ route('measurements.index') }}">
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
                        <option value="">Todas las variables</option>
                        @foreach ($variables as $variable)
                            <option value="{{ $variable->id }}" @selected((string) ($filters['measurement_variable_id'] ?? '') === (string) $variable->id)>
                                {{ $variable->name }} ({{ $variable->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="status">Estado</label>
                    <select id="status" name="status">
                        <option value="">Todos los estados</option>
                        <option value="recorded" @selected(($filters['status'] ?? '') === 'recorded')>Dentro de rango</option>
                        <option value="out_of_range" @selected(($filters['status'] ?? '') === 'out_of_range')>Fuera de rango</option>
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

                <div class="field">
                    <label for="shift">Turno</label>
                    <select id="shift" name="shift">
                        <option value="">Todos los turnos</option>
                        <option value="manana" @selected(($filters['shift'] ?? '') === 'manana')>Manana</option>
                        <option value="tarde" @selected(($filters['shift'] ?? '') === 'tarde')>Tarde</option>
                        <option value="noche" @selected(($filters['shift'] ?? '') === 'noche')>Noche</option>
                    </select>
                </div>
            </div>

            <div class="actions" style="margin-top: 16px;">
                <button class="button" type="submit">Aplicar filtros</button>
                <a class="button secondary" href="{{ route('measurements.index') }}">Limpiar</a>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>Registros ({{ $measurements->total() }})</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha y hora</th>
                    <th>Area</th>
                    <th>Variable</th>
                    <th>Valor</th>
                    <th>Turno</th>
                    <th>Estado</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($measurements as $measurement)
                    <tr>
                        <td>{{ $measurement->measured_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $measurement->variable->area->name }}</td>
                        <td>{{ $measurement->variable->name }}<br><span class="muted">{{ $measurement->variable->frequency->name }}</span></td>
                        <td>{{ $measurement->value }} {{ $measurement->variable->unit }}</td>
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
                        <td colspan="7" class="muted">No hay mediciones para los filtros seleccionados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($measurements->hasPages())
            <div class="pagination">
                {{ $measurements->onEachSide(1)->links() }}
            </div>
        @endif
    </section>
@endsection
