<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataPlant</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f7f6; color: #17211f; }
        header { background: #10352f; color: #fff; padding: 22px 32px; }
        main { max-width: 1100px; margin: 0 auto; padding: 28px; }
        h1 { margin: 0 0 6px; font-size: 34px; }
        h2 { margin-top: 0; font-size: 20px; }
        .subtitle { margin: 0; color: #c6d8d3; }
        .grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin: 24px 0; }
        .panel, .metric { background: #fff; border: 1px solid #d9e3e0; border-radius: 6px; padding: 18px; }
        .metric strong { display: block; font-size: 32px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border-bottom: 1px solid #d9e3e0; padding: 11px; text-align: left; }
        th { background: #e8efed; }
        .muted { color: #63746f; }
        @media (max-width: 760px) { .grid { grid-template-columns: 1fr; } main { padding: 18px; } }
    </style>
</head>
<body>
<header>
    <h1>DataPlant</h1>
    <p class="subtitle">Sistema industrial de mediciones, auditoria y tableros operativos.</p>
</header>

<main>
    <section class="grid">
        <div class="metric">
            <strong>{{ $areaCount }}</strong>
            <span>Areas activas</span>
        </div>
        <div class="metric">
            <strong>{{ $variableCount }}</strong>
            <span>Variables definidas</span>
        </div>
        <div class="metric">
            <strong>{{ $measurementCount }}</strong>
            <span>Mediciones registradas</span>
        </div>
    </section>

    <section class="panel">
        <h2>Ultimas mediciones</h2>

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Area</th>
                    <th>Variable</th>
                    <th>Valor</th>
                    <th>Turno</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestMeasurements as $measurement)
                    <tr>
                        <td>{{ $measurement->measured_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $measurement->variable->area->name }}</td>
                        <td>{{ $measurement->variable->name }}</td>
                        <td>{{ $measurement->value }} {{ $measurement->variable->unit }}</td>
                        <td>{{ $measurement->shift ?: 'No aplica' }}</td>
                        <td>{{ $measurement->user?->name ?: 'Sistema' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="muted">Todavia no hay mediciones. Ejecuta los seeders para cargar datos ficticios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
