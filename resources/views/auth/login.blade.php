<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar | DataPlant</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: Arial, sans-serif; background: #f4f7f6; color: #17211f; }
        .login { width: min(420px, calc(100% - 36px)); background: #fff; border: 1px solid #d9e3e0; border-radius: 6px; padding: 28px; box-sizing: border-box; }
        h1 { margin: 0 0 6px; color: #10352f; }
        .subtitle { margin: 0 0 24px; color: #63746f; }
        label { display: block; margin: 16px 0 6px; font-weight: bold; }
        input { width: 100%; box-sizing: border-box; border: 1px solid #b9cbc6; border-radius: 4px; padding: 11px; font: inherit; }
        button { width: 100%; margin-top: 22px; border: 0; border-radius: 4px; padding: 12px; background: #10352f; color: #fff; font: inherit; font-weight: bold; cursor: pointer; }
        .remember { display: flex; gap: 8px; align-items: center; margin-top: 14px; color: #63746f; font-size: 14px; }
        .remember input { width: auto; }
        .error { margin: 6px 0 0; color: #a52b2b; font-size: 14px; }
        .demo { margin: 20px 0 0; padding-top: 16px; border-top: 1px solid #e2ebe8; color: #63746f; font-size: 13px; }
    </style>
</head>
<body>
    <main class="login">
        <h1>DataPlant</h1>
        <p class="subtitle">Acceso al sistema de mediciones industriales.</p>

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="email">Correo electronico</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">

            <label for="password">Contrasena</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">

            <label class="remember">
                <input name="remember" type="checkbox" value="1">
                Mantener la sesion iniciada
            </label>

            <button type="submit">Ingresar</button>
        </form>

        <p class="demo">Demo: admin@dataplant.test o operario@dataplant.test. Contrasena: password.</p>
    </main>
</body>
</html>
