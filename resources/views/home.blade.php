@extends('layouts.app')

@section('title', 'Inicio - Sistema de formularios')

@section('content')
    <h1>Inicio</h1>

    <section class="panel">
        <p>Bienvenido, {{ auth()->user()->name }}.</p>
        <p class="muted">Rol actual: {{ auth()->user()->role }}</p>
    </section>

    <section class="summary-grid">
        <div class="summary-item">
            <span class="summary-number">{{ $totalMediciones }}</span>
            <span>Mediciones cargadas</span>
        </div>
        <div class="summary-item">
            <span class="summary-number">{{ $totalInspecciones }}</span>
            <span>Inspecciones cargadas</span>
        </div>
    </section>

    <section class="panel">
        <h2>Modulos disponibles</h2>
        <div class="actions">
            <a class="btn" href="{{ route('mediciones.index') }}">Ver mediciones</a>
            <a class="btn secondary" href="{{ route('inspecciones.index') }}">Ver inspecciones</a>
        </div>
    </section>

    <section class="panel">
        <h2>Ultimas mediciones</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Turno</th>
                    <th>Valor</th>
                    <th>Cargado por</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ultimasMediciones as $medicion)
                    <tr>
                        <td>{{ $medicion->fecha }}</td>
                        <td>{{ $medicion->turno }}</td>
                        <td>{{ $medicion->valor }}</td>
                        <td>{{ $medicion->usuario?->name ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Todavia no hay mediciones cargadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="panel">
        <h2>Ultimas inspecciones</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Sector</th>
                    <th>Estado</th>
                    <th>Cargado por</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ultimasInspecciones as $inspeccion)
                    <tr>
                        <td>{{ $inspeccion->fecha }}</td>
                        <td>{{ $inspeccion->sector }}</td>
                        <td>{{ $inspeccion->estado }}</td>
                        <td>{{ $inspeccion->usuario?->name ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Todavia no hay inspecciones cargadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
