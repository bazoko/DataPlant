<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DataPlant')</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f7f6; color: #17211f; }
        a { color: #14584d; }
        header { background: #10352f; color: #fff; }
        .container { width: min(1100px, calc(100% - 36px)); margin: 0 auto; }
        .header-inner { min-height: 74px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { color: #fff; font-size: 22px; font-weight: bold; text-decoration: none; }
        nav { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
        nav a { color: #d6e8e3; text-decoration: none; font-size: 14px; }
        nav a:hover { color: #fff; }
        .account { display: flex; align-items: center; gap: 10px; color: #d6e8e3; font-size: 13px; }
        .role { display: inline-block; color: #10352f; background: #dcebe7; border-radius: 12px; padding: 3px 8px; font-size: 12px; }
        .link-button { border: 0; padding: 0; background: none; color: #d6e8e3; font: inherit; cursor: pointer; }
        main { padding: 30px 0 48px; }
        h1, h2, h3 { color: #10352f; }
        h1 { margin: 0 0 8px; font-size: 30px; }
        h2 { margin: 0 0 16px; font-size: 20px; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 24px; }
        .page-heading p { margin: 0; color: #63746f; }
        .eyebrow { margin-bottom: 6px !important; color: #4e8075 !important; font-size: 12px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
        .actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .button { display: inline-block; border: 1px solid #14584d; border-radius: 4px; padding: 10px 13px; background: #14584d; color: #fff; text-decoration: none; font: inherit; cursor: pointer; }
        .button.secondary { background: #fff; color: #14584d; }
        .grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; margin: 24px 0; }
        .kpi-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); }
        .metric, .panel { background: #fff; border: 1px solid #d9e3e0; border-radius: 6px; padding: 18px; }
        .metric strong { display: block; margin-bottom: 4px; color: #10352f; font-size: 32px; }
        .metric span, .muted, .helper { color: #63746f; }
        .panel { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border-bottom: 1px solid #d9e3e0; padding: 11px; text-align: left; vertical-align: top; }
        th { background: #e8efed; color: #10352f; font-size: 13px; }
        .badge { display: inline-block; border-radius: 12px; padding: 4px 8px; background: #dcebe7; color: #14584d; font-size: 12px; }
        .badge.danger { background: #f8dfdf; color: #9c2d2d; }
        .chart-wrap { width: 100%; overflow-x: auto; }
        .chart { display: block; width: 100%; min-width: 680px; height: auto; }
        .chart-gridline { stroke: #d9e3e0; stroke-width: 1; }
        .chart-axis-label, .chart-caption { fill: #63746f; color: #63746f; font-size: 12px; }
        .chart-line { fill: none; stroke: #14584d; stroke-linecap: round; stroke-linejoin: round; stroke-width: 3; }
        .chart-point { fill: #fff; stroke: #14584d; stroke-width: 3; }
        .chart-heading { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
        .chart-heading p { margin: 4px 0 0; color: #63746f; }
        .filters, .form-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .field { display: flex; flex-direction: column; gap: 6px; }
        .field.full { grid-column: 1 / -1; }
        label { color: #24443e; font-size: 14px; font-weight: bold; }
        input, select, textarea { width: 100%; border: 1px solid #b9cbc6; border-radius: 4px; padding: 10px; background: #fff; color: #17211f; font: inherit; }
        textarea { min-height: 100px; resize: vertical; }
        .error-list, .success { border-radius: 4px; padding: 12px 14px; }
        .error-list { margin-bottom: 18px; background: #f8dfdf; color: #9c2d2d; }
        .error-list ul { margin: 0; padding-left: 20px; }
        .success { margin-bottom: 18px; background: #dcebe7; color: #14584d; }
        .pagination { display: flex; gap: 6px; margin-top: 16px; }
        .pagination a, .pagination span { border: 1px solid #d9e3e0; border-radius: 4px; padding: 7px 10px; text-decoration: none; }
        .pagination span { background: #e8efed; color: #63746f; }
        @media (max-width: 1050px) { .kpi-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 800px) { .header-inner, .page-heading { align-items: flex-start; flex-direction: column; } .account { align-items: flex-start; } .grid, .filters, .form-grid { grid-template-columns: 1fr; } .kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .field.full { grid-column: auto; } .panel { overflow-x: auto; } table { min-width: 720px; } }
    </style>
</head>
<body>
    <header>
        <div class="container header-inner">
            <a class="brand" href="{{ route('dashboard') }}">DataPlant</a>
            <nav aria-label="Navegacion principal">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @can('measurements.view')
                    <a href="{{ route('measurements.index') }}">Mediciones</a>
                @endcan
                @can('users.manage')
                    <a href="{{ route('users.index') }}">Usuarios</a>
                @endcan
            </nav>
            <div class="account">
                <span>{{ auth()->user()->name }}</span>
                <span class="role">{{ auth()->user()->getRoleNames()->first() ?: 'sin rol' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="link-button" type="submit">Salir</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        @if (session('status'))
            <div class="success">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
