<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de formularios')</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f7fb; color: #1f2937; }
        header { background: #172554; color: white; padding: 14px 24px; }
        header .bar { display: flex; align-items: center; justify-content: space-between; gap: 16px; max-width: 1100px; margin: 0 auto; }
        nav { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        nav a, nav button { color: white; background: transparent; border: 1px solid rgba(255,255,255,.35); padding: 8px 10px; text-decoration: none; cursor: pointer; font: inherit; }
        main { max-width: 1100px; margin: 0 auto; padding: 24px; }
        h1 { margin-top: 0; font-size: 28px; }
        .panel { background: white; border: 1px solid #d7dde7; padding: 20px; margin-bottom: 18px; }
        .summary-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 18px; }
        .summary-item { background: white; border: 1px solid #d7dde7; padding: 18px; }
        .summary-number { display: block; font-size: 34px; font-weight: bold; margin-bottom: 4px; }
        .filter-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; align-items: end; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 9px 12px; text-decoration: none; border: 0; cursor: pointer; }
        .btn.secondary { background: #475569; }
        .btn.danger { background: #b91c1c; }
        .inline-form { display: inline; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #d7dde7; padding: 10px; text-align: left; vertical-align: top; }
        th { background: #e8edf5; }
        label { display: block; margin-top: 14px; font-weight: bold; }
        input, select, textarea { width: 100%; box-sizing: border-box; margin-top: 6px; padding: 10px; border: 1px solid #b8c0cc; font: inherit; }
        textarea { min-height: 90px; resize: vertical; }
        .status { background: #dcfce7; border: 1px solid #86efac; padding: 10px; margin-bottom: 16px; }
        .errors { background: #fee2e2; border: 1px solid #fca5a5; padding: 10px; margin-bottom: 16px; color: #991b1b; }
        .muted { color: #64748b; }
        @media (max-width: 720px) {
            header .bar { align-items: flex-start; flex-direction: column; }
            main { padding: 16px; }
            table { font-size: 14px; }
            .summary-grid, .filter-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<header>
    <div class="bar">
        <strong>Sistema de formularios</strong>
        @auth
            <nav>
                <a href="{{ route('home') }}">Inicio</a>
                <a href="{{ route('mediciones.index') }}">Mediciones</a>
                @if (auth()->user()->hasRole(['admin', 'carga']))
                    <a href="{{ route('mediciones.create') }}">Nueva medicion</a>
                @endif
                <a href="{{ route('inspecciones.index') }}">Inspecciones</a>
                @if (auth()->user()->hasRole(['admin', 'carga']))
                    <a href="{{ route('inspecciones.create') }}">Nueva inspeccion</a>
                @endif
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('actividad.index') }}">Actividad</a>
                    <a href="{{ route('usuarios.index') }}">Usuarios</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Salir</button>
                </form>
            </nav>
        @endauth
    </div>
</header>

<main>
    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <strong>Revisa los datos ingresados.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>
