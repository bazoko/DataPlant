@extends('layouts.app')

@section('title', 'Mediciones')

@section('content')
    <h1>Mediciones</h1>

    @if (auth()->user()->hasRole(['admin', 'carga']))
        <div class="actions">
            <a class="btn" href="{{ route('mediciones.create') }}">Nueva medicion</a>
        </div>
    @endif

    <form class="panel" method="GET" action="{{ route('mediciones.index') }}">
        <h2>Filtros</h2>
        <div class="filter-grid">
            <div>
                <label for="fecha_desde">Fecha desde</label>
                <input id="fecha_desde" name="fecha_desde" type="date" value="{{ $filters['fecha_desde'] ?? '' }}">
            </div>
            <div>
                <label for="fecha_hasta">Fecha hasta</label>
                <input id="fecha_hasta" name="fecha_hasta" type="date" value="{{ $filters['fecha_hasta'] ?? '' }}">
            </div>
            <div>
                <label for="turno">Turno</label>
                <select id="turno" name="turno">
                    <option value="">Todos</option>
                    <option value="manana" @selected(($filters['turno'] ?? '') === 'manana')>Manana</option>
                    <option value="tarde" @selected(($filters['turno'] ?? '') === 'tarde')>Tarde</option>
                    <option value="noche" @selected(($filters['turno'] ?? '') === 'noche')>Noche</option>
                </select>
            </div>
            <div class="actions">
                <button class="btn" type="submit">Filtrar</button>
                <a class="btn secondary" href="{{ route('mediciones.index') }}">Limpiar</a>
            </div>
        </div>
    </form>

    <div class="actions">
        <a class="btn secondary" href="{{ route('mediciones.export', request()->query()) }}">Exportar CSV</a>
    </div>

    <p class="muted">Registros encontrados: {{ $mediciones->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Turno</th>
                <th>Valor</th>
                <th>Observacion</th>
                <th>Cargado por</th>
                @if (auth()->user()->hasRole('admin'))
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($mediciones as $medicion)
                <tr>
                    <td>{{ $medicion->fecha }}</td>
                    <td>{{ $medicion->turno }}</td>
                    <td>{{ $medicion->valor }}</td>
                    <td>{{ $medicion->observacion ?: '-' }}</td>
                    <td>{{ $medicion->usuario?->name ?: '-' }}</td>
                    @if (auth()->user()->hasRole('admin'))
                        <td>
                            <a class="btn secondary" href="{{ route('mediciones.edit', $medicion) }}">Editar</a>
                            <form class="inline-form" method="POST" action="{{ route('mediciones.destroy', $medicion) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger" type="submit">Eliminar</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->hasRole('admin') ? 6 : 5 }}">Todavia no hay mediciones cargadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
