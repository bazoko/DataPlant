# Rutas web

Las rutas actuales se encuentran en `routes/web.php`. El middleware se muestra porque define tanto el acceso tecnico como el permiso funcional.

## Entrada y autenticacion

| Metodo | URL | Middleware | Funcion |
| --- | --- | --- | --- |
| GET | `/` | Ninguno | Redirige al dashboard como punto de entrada. |
| GET | `/login` | `guest` | Muestra el formulario de ingreso. |
| POST | `/login` | `guest` | Valida credenciales e inicia la sesion. |
| POST | `/logout` | `auth` | Cierra la sesion y regresa al login. |

## Operacion actual

| Metodo | URL | Middleware | Funcion |
| --- | --- | --- | --- |
| GET | `/dashboard` | `auth`, `permission:dashboard.view` | Muestra totales y ultimas mediciones. |
| GET | `/usuarios` | `auth`, `permission:users.manage` | Lista usuarios y sus roles. |
| GET | `/mediciones` | `auth`, `permission:measurements.view` | Muestra el historial de mediciones con filtros. |
| GET | `/mediciones/crear` | `auth`, `permission:measurements.create` | Muestra el formulario de carga. |
| POST | `/mediciones` | `auth`, `permission:measurements.create` | Valida y guarda una medicion y su auditoria. |

## Como leer una ruta

Ejemplo:

```php
Route::get('/usuarios', function () {
    // Prepara usuarios con sus roles y devuelve una vista.
})->middleware('permission:users.manage')->name('users.index');
```

El navegador solicita `/usuarios`, Laravel verifica primero la sesion y despues el permiso. Solo si ambas condiciones se cumplen se consulta la base y se renderiza la vista.

## Proximas rutas del modulo de mediciones

Estas operaciones todavia no estan implementadas:

| Metodo | URL | Permiso esperado | Funcion |
| --- | --- | --- | --- |
| GET | `/mediciones/{measurement}` | `measurements.view` | Detalle de una medicion. |

Cuando estas rutas crezcan, las operaciones pasaran a `MeasurementController` para que `routes/web.php` solo declare el mapa de la aplicacion.
