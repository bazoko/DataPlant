@extends('layouts.app')

@section('title', 'Inspecciones')

@section('content')
    <h1>Inspecciones</h1>

    @if (auth()->user()->hasRole(['admin', 'carga']))
        <div class="actions">
            <a class="btn" href="{{ route('inspecciones.create') }}">Nueva inspeccion</a>
        </div>
    @endif

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
