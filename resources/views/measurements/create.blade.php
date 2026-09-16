@extends('layouts.app')

@section('title', 'Registrar medicion | DataPlant')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Efluentes / PTAR</p>
            <h1>Registrar medicion</h1>
            <p>El sistema guardara el usuario, fecha, hora, frecuencia y estado del valor.</p>
        </div>
        <a class="button secondary" href="{{ route('measurements.index') }}">Ver historial</a>
    </div>

    @if ($errors->any())
        <div class="error-list">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="panel">
        <form method="POST" action="{{ route('measurements.store') }}">
            @csrf
            <div class="form-grid">
                <div class="field full">
                    <label for="measurement_variable_id">Variable</label>
                    <select id="measurement_variable_id" name="measurement_variable_id" required>
                        <option value="">Seleccionar variable</option>
                        @foreach ($variables as $variable)
                            <option
                                value="{{ $variable->id }}"
                                data-requires-shift="{{ $variable->frequency->requires_shift ? '1' : '0' }}"
                                data-unit="{{ $variable->unit }}"
                                data-min="{{ $variable->min_value }}"
                                data-max="{{ $variable->max_value }}"
                                @selected((string) old('measurement_variable_id') === (string) $variable->id)
                            >
                                {{ $variable->area->name }} - {{ $variable->name }} ({{ $variable->frequency->name }}, {{ $variable->unit }})
                            </option>
                        @endforeach
                    </select>
                    <span id="variable-meta" class="helper">Selecciona una variable para ver sus reglas de captura.</span>
                </div>

                <div class="field">
                    <label for="measured_date">Fecha de medicion</label>
                    <input id="measured_date" name="measured_date" type="date" value="{{ old('measured_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="field">
                    <label for="measured_time">Hora de medicion</label>
                    <input id="measured_time" name="measured_time" type="time" value="{{ old('measured_time', now()->format('H:i')) }}" required>
                </div>

                <div id="shift-field" class="field">
                    <label for="shift">Turno</label>
                    <select id="shift" name="shift">
                        <option value="">No aplica</option>
                        <option value="manana" @selected(old('shift') === 'manana')>Manana</option>
                        <option value="tarde" @selected(old('shift') === 'tarde')>Tarde</option>
                        <option value="noche" @selected(old('shift') === 'noche')>Noche</option>
                    </select>
                </div>

                <div class="field">
                    <label for="value">Valor</label>
                    <input id="value" name="value" type="number" step="0.0001" value="{{ old('value') }}" required>
                </div>

                <div class="field full">
                    <label for="observation">Observacion</label>
                    <textarea id="observation" name="observation" maxlength="1000" placeholder="Agregar contexto operativo si es necesario">{{ old('observation') }}</textarea>
                </div>
            </div>

            <div class="actions" style="margin-top: 20px;">
                <button class="button" type="submit">Guardar medicion</button>
                <a class="button secondary" href="{{ route('measurements.index') }}">Cancelar</a>
            </div>
        </form>
    </section>

    <script>
        const variableSelect = document.getElementById('measurement_variable_id');
        const shiftField = document.getElementById('shift-field');
        const shiftInput = document.getElementById('shift');
        const variableMeta = document.getElementById('variable-meta');

        function updateVariableRules() {
            const option = variableSelect.options[variableSelect.selectedIndex];
            const requiresShift = option?.dataset.requiresShift === '1';

            shiftField.hidden = !requiresShift;
            shiftInput.required = requiresShift;
            if (!requiresShift) {
                shiftInput.value = '';
            }

            if (!option?.value) {
                variableMeta.textContent = 'Selecciona una variable para ver sus reglas de captura.';
                return;
            }

            const limits = [];
            if (option.dataset.min !== '') limits.push(`minimo ${option.dataset.min}`);
            if (option.dataset.max !== '') limits.push(`maximo ${option.dataset.max}`);
            variableMeta.textContent = limits.length > 0
                ? `Unidad: ${option.dataset.unit}. Limites: ${limits.join(', ')}.`
                : `Unidad: ${option.dataset.unit}. Sin limites configurados.`;
        }

        variableSelect.addEventListener('change', updateVariableRules);
        updateVariableRules();
    </script>
@endsection
