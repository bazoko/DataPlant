<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso - Sistema de formularios</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #eef2f7; color: #1f2937; }
        main { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .login-box { width: 100%; max-width: 380px; background: white; border: 1px solid #d7dde7; padding: 24px; }
        h1 { margin: 0 0 16px; font-size: 24px; }
        label { display: block; margin-top: 14px; font-weight: bold; }
        input { width: 100%; box-sizing: border-box; margin-top: 6px; padding: 10px; border: 1px solid #b8c0cc; }
        button { width: 100%; margin-top: 20px; padding: 11px; border: 0; background: #2563eb; color: white; font-weight: bold; cursor: pointer; }
        .error { margin-top: 12px; color: #b91c1c; }
        .help { margin-top: 16px; font-size: 14px; color: #4b5563; line-height: 1.5; }
    </style>
</head>
<body>
<main>
    <section class="login-box">
        <h1>Ingreso al sistema</h1>

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Contrasena</label>
            <input id="password" name="password" type="password" required>

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <button type="submit">Entrar</button>
        </form>

        <div class="help">
            Usuarios de prueba:<br>
            admin@example.com / password<br>
            carga@example.com / password<br>
            consulta@example.com / password
        </div>
    </section>
</main>
</body>
</html>
