@extends('layouts.app')

@section('title', 'Actividad')

@section('content')
    <h1>Actividad</h1>

    <section class="panel">
        <p class="muted">Ultimas 100 acciones registradas en el sistema.</p>
    </section>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Modulo</th>
                <th>Accion</th>
                <th>Descripcion</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($actividades as $actividad)
                <tr>
                    <td>{{ $actividad->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $actividad->usuario?->name ?: 'Sistema' }}</td>
                    <td>{{ $actividad->modulo }}</td>
                    <td>{{ $actividad->accion }}</td>
                    <td>{{ $actividad->descripcion }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Todavia no hay actividad registrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
