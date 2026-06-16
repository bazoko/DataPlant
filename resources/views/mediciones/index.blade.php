@extends('layouts.app')

@section('title', 'Mediciones')

@section('content')
    <h1>Mediciones</h1>

    @if (auth()->user()->hasRole(['admin', 'carga']))
        <div class="actions">
            <a class="btn" href="{{ route('mediciones.create') }}">Nueva medicion</a>
        </div>
    @endif

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
