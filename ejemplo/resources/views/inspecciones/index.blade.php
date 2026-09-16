@extends('layouts.app')

@section('title', 'Inspecciones')

@section('content')
    <h1>Inspecciones</h1>

    @if (auth()->user()->hasRole(['admin', 'carga']))
        <div class="actions">
            <a class="btn" href="{{ route('inspecciones.create') }}">Nueva inspeccion</a>
        </div>
    @endif

    <form class="panel" method="GET" action="{{ route('inspecciones.index') }}">
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
                <label for="sector">Sector</label>
                <input id="sector" name="sector" type="text" value="{{ $filters['sector'] ?? '' }}">
            </div>
            <div>
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="correcto" @selected(($filters['estado'] ?? '') === 'correcto')>Correcto</option>
                    <option value="observado" @selected(($filters['estado'] ?? '') === 'observado')>Observado</option>
                    <option value="critico" @selected(($filters['estado'] ?? '') === 'critico')>Critico</option>
                </select>
            </div>
        </div>
        <div class="actions">
            <button class="btn" type="submit">Filtrar</button>
            <a class="btn secondary" href="{{ route('inspecciones.index') }}">Limpiar</a>
        </div>
    </form>

    <div class="actions">
        <a class="btn secondary" href="{{ route('inspecciones.export', request()->query()) }}">Exportar CSV</a>
    </div>

    <p class="muted">Registros encontrados: {{ $inspecciones->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Sector</th>
                <th>Estado</th>
                <th>Observacion</th>
                <th>Cargado por</th>
                @if (auth()->user()->hasRole('admin'))
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($inspecciones as $inspeccion)
                <tr>
                    <td>{{ $inspeccion->fecha }}</td>
                    <td>{{ $inspeccion->sector }}</td>
                    <td>{{ $inspeccion->estado }}</td>
                    <td>{{ $inspeccion->observacion ?: '-' }}</td>
                    <td>{{ $inspeccion->usuario?->name ?: '-' }}</td>
                    @if (auth()->user()->hasRole('admin'))
                        <td>
                            <a class="btn secondary" href="{{ route('inspecciones.edit', $inspeccion) }}">Editar</a>
                            <form class="inline-form" method="POST" action="{{ route('inspecciones.destroy', $inspeccion) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger" type="submit">Eliminar</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->hasRole('admin') ? 6 : 5 }}">Todavia no hay inspecciones cargadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
