# DataPlant

DataPlant es una aplicacion Laravel para registrar, organizar y visualizar mediciones operativas de una fabrica tipo Softys.

El objetivo es reemplazar planillas dispersas por una base de datos estructurada con usuarios, areas, variables, frecuencias de medicion, auditoria y tableros operativos.

## Enfoque actual

Este repositorio se reinicio desde cero. El proyecto anterior quedo guardado en `ejemplo/` solo como referencia.

Decisiones base:

- Laravel MVC.
- Base de datos principal: MySQL.
- Primer modulo industrial: Efluentes / PTAR.
- Areas como agrupacion principal.
- Sin division obligatoria en lineas y sectores.
- Frecuencias flexibles: diaria, por turno, horaria y personalizada.
- LILA queda para una etapa futura.
- Roles/permisos profesionales con `spatie/laravel-permission`.

## Modelo inicial

El primer corte incluye:

- `areas`
- `measurement_frequencies`
- `measurement_variables`
- `measurements`
- `activity_logs`
- usuarios demo
- carga e historial de mediciones con filtros

Los datos de prueba son ficticios y seguros para portfolio.

## Requisitos

- PHP 8.3+
- Composer
- Node.js y npm
- MySQL

## Configuracion

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Crear una base MySQL llamada:

```text
dataplant
```

Configurar `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dataplant
DB_USERNAME=root
DB_PASSWORD=
```

Migrar y cargar datos ficticios:

```bash
php artisan migrate --seed
```

Levantar:

```bash
php artisan serve
```

Abrir:

```text
http://127.0.0.1:8000
```

El dashboard requiere iniciar sesion. El acceso de usuarios se controla con roles y permisos de Spatie.

El modulo inicial de mediciones permite registrar datos de Efluentes / PTAR, respetando variables diarias, por turno u horarias. Cada registro guarda el usuario, fecha, hora, unidad, observacion y estado respecto de sus limites.

## Usuarios demo

Todos usan la contrasena `password`.

| Rol conceptual | Email |
| --- | --- |
| Administrador | `admin@dataplant.test` |
| Responsable de turno | `operario@dataplant.test` |

El administrador puede consultar `/usuarios`. El responsable de turno puede acceder al dashboard, pero no administrar usuarios.

## Permisos profesionales

La opcion elegida es `spatie/laravel-permission`.

Ya esta instalado y publicado. Despues de instalar dependencias en un entorno nuevo, ejecutar:

```bash
php artisan migrate
```

Roles iniciales:

- `admin`
- `supervisor`
- `operario`
- `consulta`

Permisos iniciales:

- `areas.view`
- `areas.manage`
- `measurements.view`
- `measurements.create`
- `dashboard.view`
- `users.manage`
- `roles.manage`
- `audit.view`

## Verificacion

```bash
php artisan test
```

## Documentacion guiada

La carpeta [`docs/`](docs/README.md) explica la estructura del proyecto, los flujos de autenticacion y autorizacion, el recorrido de una medicion, las rutas actuales y la matriz de roles y permisos.

## Portfolio GitHub

Este proyecto esta pensado para quedar prolijo en GitHub y servir como muestra profesional:

- README claro.
- Datos ficticios.
- Sin `.env`.
- Sin `vendor/` ni `node_modules/`.
- Commits descriptivos.
- Explicacion del problema industrial que resuelve.
