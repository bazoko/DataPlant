# Documentacion de controladores

Los controladores se encuentran en `app/Http/Controllers`.

## AuthController

Archivo: `app/Http/Controllers/AuthController.php`

Responsabilidad: gestionar login y logout.

Metodos:

- `showLogin()`: muestra el formulario de ingreso.
- `login()`: valida email y contrasena, inicia sesion y redirige al inicio.
- `logout()`: cierra la sesion y redirige al login.

## HomeController

Archivo: `app/Http/Controllers/HomeController.php`

Responsabilidad: mostrar la pantalla principal del sistema.

Metodos:

- `index()`: retorna `home.blade.php`.

## MedicionController

Archivo: `app/Http/Controllers/MedicionController.php`

Responsabilidad: administrar el formulario y listado de mediciones.

Metodos:

- `index()`: obtiene mediciones con el usuario que las cargo y retorna `mediciones/index.blade.php`.
- `create()`: retorna el formulario `mediciones/create.blade.php`.
- `store()`: valida datos, asigna el usuario autenticado y guarda la medicion.
- `edit()`: muestra el formulario de edicion de una medicion.
- `update()`: valida datos y actualiza una medicion existente.
- `destroy()`: elimina una medicion existente.

Validaciones principales:

- `fecha`: obligatoria y tipo fecha.
- `turno`: obligatorio, valores permitidos `manana`, `tarde`, `noche`.
- `valor`: obligatorio y numerico.
- `observacion`: opcional, maximo 500 caracteres.

## InspeccionController

Archivo: `app/Http/Controllers/InspeccionController.php`

Responsabilidad: administrar el formulario y listado de inspecciones.

Metodos:

- `index()`: obtiene inspecciones con el usuario que las cargo y retorna `inspecciones/index.blade.php`.
- `create()`: retorna el formulario `inspecciones/create.blade.php`.
- `store()`: valida datos, asigna el usuario autenticado y guarda la inspeccion.
- `edit()`: muestra el formulario de edicion de una inspeccion.
- `update()`: valida datos y actualiza una inspeccion existente.
- `destroy()`: elimina una inspeccion existente.

Validaciones principales:

- `fecha`: obligatoria y tipo fecha.
- `sector`: obligatorio, texto, maximo 100 caracteres.
- `estado`: obligatorio, valores permitidos `correcto`, `observado`, `critico`.
- `observacion`: opcional, maximo 500 caracteres.

## RoleMiddleware

Archivo: `app/Http/Middleware/RoleMiddleware.php`

Responsabilidad: permitir o bloquear rutas segun el rol del usuario autenticado.
