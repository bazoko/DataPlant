@extends('layouts.app')

@section('title', 'Editar inspeccion')

@section('content')
    <h1>Editar inspeccion</h1>

    <form class="panel" method="POST" action="{{ route('inspecciones.update', $inspeccion) }}">
        @csrf
        @method('PUT')

        <label for="fecha">Fecha</label>
        <input id="fecha" name="fecha" type="date" value="{{ old('fecha', $inspeccion->fecha) }}" required>

        <label for="sector">Sector</label>
        <input id="sector" name="sector" type="text" value="{{ old('sector', $inspeccion->sector) }}" maxlength="100" required>

        <label for="estado">Estado</label>
        <select id="estado" name="estado" required>
            <option value="">Seleccionar</option>
            <option value="correcto" @selected(old('estado', $inspeccion->estado) === 'correcto')>Correcto</option>
            <option value="observado" @selected(old('estado', $inspeccion->estado) === 'observado')>Observado</option>
            <option value="critico" @selected(old('estado', $inspeccion->estado) === 'critico')>Critico</option>
        </select>

        <label for="observacion">Observacion</label>
        <textarea id="observacion" name="observacion" maxlength="500">{{ old('observacion', $inspeccion->observacion) }}</textarea>

        <button class="btn" type="submit">Actualizar inspeccion</button>
        <a class="btn secondary" href="{{ route('inspecciones.index') }}">Volver</a>
    </form>
@endsection
