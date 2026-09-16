@extends('layouts.app')

@section('title', 'Nueva medicion')

@section('content')
    <h1>Nueva medicion</h1>

    <form class="panel" method="POST" action="{{ route('mediciones.store') }}">
        @csrf

        <label for="fecha">Fecha</label>
        <input id="fecha" name="fecha" type="date" value="{{ old('fecha') }}" required>

        <label for="turno">Turno</label>
        <select id="turno" name="turno" required>
            <option value="">Seleccionar</option>
            <option value="manana" @selected(old('turno') === 'manana')>Manana</option>
            <option value="tarde" @selected(old('turno') === 'tarde')>Tarde</option>
            <option value="noche" @selected(old('turno') === 'noche')>Noche</option>
        </select>

        <label for="valor">Valor</label>
        <input id="valor" name="valor" type="number" step="0.01" value="{{ old('valor') }}" required>

        <label for="observacion">Observacion</label>
        <textarea id="observacion" name="observacion" maxlength="500">{{ old('observacion') }}</textarea>

        <button class="btn" type="submit">Guardar medicion</button>
    </form>
@endsection
