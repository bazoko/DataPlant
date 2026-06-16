# Estructura del proyecto

## app

Contiene la logica principal de la aplicacion.

### Models

Representan tablas de la base de datos.

Archivos actuales:

- `app/Models/User.php`
- `app/Models/Medicion.php`
- `app/Models/Inspeccion.php`

### Http/Controllers

Contiene los controladores MVC.

Archivos actuales:

- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/MedicionController.php`
- `app/Http/Controllers/InspeccionController.php`

### Http/Middleware

Contiene middleware de permisos.

Archivos actuales:

- `app/Http/Middleware/RoleMiddleware.php`

## database

Contiene migraciones, seeders y la base SQLite local.

Tablas principales:

- `users`
- `mediciones`
- `inspecciones`
- `sessions`

## resources/views

Contiene las vistas Blade.

Vistas actuales:

- `auth/login.blade.php`
- `layouts/app.blade.php`
- `home.blade.php`
- `mediciones/index.blade.php`
- `mediciones/create.blade.php`
- `mediciones/edit.blade.php`
- `inspecciones/index.blade.php`
- `inspecciones/create.blade.php`
- `inspecciones/edit.blade.php`

## routes

Contiene las rutas del sistema.

Archivo principal:

- `routes/web.php`

## public

Contiene el punto de entrada publico `index.php` y archivos publicos.

## docs

Contiene la documentacion del proyecto:

- `docs/RUTAS.md`
- `docs/CONTROLADORES.md`
- `docs/FLUJO_PROYECTO.md`
- `docs/ESTRUCTURA_PROYECTO.md`

## Arquitectura MVC

Modelo: representa y guarda datos.

Vista: muestra formularios y listados.

Controlador: conecta rutas, modelos y vistas.
