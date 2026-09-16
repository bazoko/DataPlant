@extends('layouts.app')

@section('title', 'Nueva inspeccion')

@section('content')
    <h1>Nueva inspeccion</h1>

    <form class="panel" method="POST" action="{{ route('inspecciones.store') }}">
        @csrf

        <label for="fecha">Fecha</label>
        <input id="fecha" name="fecha" type="date" value="{{ old('fecha') }}" required>

        <label for="sector">Sector</label>
        <input id="sector" name="sector" type="text" value="{{ old('sector') }}" maxlength="100" required>

        <label for="estado">Estado</label>
        <select id="estado" name="estado" required>
            <option value="">Seleccionar</option>
            <option value="correcto" @selected(old('estado') === 'correcto')>Correcto</option>
            <option value="observado" @selected(old('estado') === 'observado')>Observado</option>
            <option value="critico" @selected(old('estado') === 'critico')>Critico</option>
        </select>

        <label for="observacion">Observacion</label>
        <textarea id="observacion" name="observacion" maxlength="500">{{ old('observacion') }}</textarea>

        <button class="btn" type="submit">Guardar inspeccion</button>
    </form>
@endsection
