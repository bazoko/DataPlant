# Sistema de formularios Laravel

Aplicacion Laravel MVC para recoleccion y consulta de datos mediante formularios.

## Funcionalidades actuales

- Proyecto Laravel funcionando.
- Base de datos SQLite configurada.
- Login de usuarios.
- Roles basicos: `admin`, `carga`, `consulta`.
- Gestion basica de usuarios para administradores.
- Registro de actividad para auditar acciones importantes.
- Dos formularios funcionales:
  - Mediciones.
  - Inspecciones.
- Listados para consultar datos cargados.
- Exportacion CSV de mediciones e inspecciones.
- Navegacion simple entre pantallas.
- Documentacion en `docs/`.
- Datos de prueba para probar filtros y exportaciones.

## Instalacion

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

En Windows PowerShell, si la base SQLite no existe:

```powershell
New-Item -ItemType File database/database.sqlite
php artisan migrate --seed
```

## Usuarios de prueba

Todos usan la contrasena `password`.

| Rol | Email |
| --- | --- |
| Admin | `admin@example.com` |
| Carga | `carga@example.com` |
| Consulta | `consulta@example.com` |

## Roles

- `admin`: puede ver y cargar datos.
- `carga`: puede ver y cargar datos.
- `consulta`: solo puede ver datos.

## Documentacion

- `docs/RUTAS.md`
- `docs/CONTROLADORES.md`
- `docs/FLUJO_PROYECTO.md`
- `docs/ESTRUCTURA_PROYECTO.md`

## Comandos utiles

```bash
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
```

## Datos de prueba

El seeder crea usuarios y registros ficticios para probar el sistema:

- 18 mediciones con fechas, turnos y valores distintos.
- 18 inspecciones con sectores y estados distintos.

Para recargar todo desde cero:

```bash
php artisan migrate:fresh --seed
```
