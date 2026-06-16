@extends('layouts.app')

@section('title', 'Editar medicion')

@section('content')
    <h1>Editar medicion</h1>

    <form class="panel" method="POST" action="{{ route('mediciones.update', $medicion) }}">
        @csrf
        @method('PUT')

        <label for="fecha">Fecha</label>
        <input id="fecha" name="fecha" type="date" value="{{ old('fecha', $medicion->fecha) }}" required>

        <label for="turno">Turno</label>
        <select id="turno" name="turno" required>
            <option value="">Seleccionar</option>
            <option value="manana" @selected(old('turno', $medicion->turno) === 'manana')>Manana</option>
            <option value="tarde" @selected(old('turno', $medicion->turno) === 'tarde')>Tarde</option>
            <option value="noche" @selected(old('turno', $medicion->turno) === 'noche')>Noche</option>
        </select>

        <label for="valor">Valor</label>
        <input id="valor" name="valor" type="number" step="0.01" value="{{ old('valor', $medicion->valor) }}" required>

        <label for="observacion">Observacion</label>
        <textarea id="observacion" name="observacion" maxlength="500">{{ old('observacion', $medicion->observacion) }}</textarea>

        <button class="btn" type="submit">Actualizar medicion</button>
        <a class="btn secondary" href="{{ route('mediciones.index') }}">Volver</a>
    </form>
@endsection
