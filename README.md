# Sistema de formularios Laravel

Aplicacion Laravel MVC para recoleccion y consulta de datos mediante formularios.

## Funcionalidades actuales

- Proyecto Laravel funcionando.
- Base de datos SQLite configurada.
- Login de usuarios.
- Roles basicos: `admin`, `carga`, `consulta`.
- Gestion basica de usuarios para administradores.
- Dos formularios funcionales:
  - Mediciones.
  - Inspecciones.
- Listados para consultar datos cargados.
- Navegacion simple entre pantallas.
- Documentacion en `docs/`.

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
