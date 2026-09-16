# Estructura del proyecto

DataPlant utiliza Laravel MVC. Cada parte tiene una responsabilidad concreta para que el sistema sea facil de mantener y de explicar.

## Carpetas principales

```text
app/
  Http/Controllers/       Entrada de las operaciones web.
  Models/                 Entidades y relaciones con MySQL.
bootstrap/                Configuracion inicial y aliases de middleware.
config/                   Configuracion de Laravel y permisos.
database/
  migrations/             Evolucion versionada del esquema.
  seeders/                Datos ficticios para desarrollo y demo.
docs/                     Documentacion funcional y tecnica.
resources/views/          Interfaz Blade que recibe los datos preparados.
routes/web.php            Mapa de URLs, middleware y acciones.
tests/                    Pruebas automaticas de los flujos importantes.
ejemplo/                  Version anterior, solo como material de referencia.
```

## Responsabilidad de cada modulo actual

### Autenticacion

- `app/Http/Controllers/AuthController.php`: muestra el login, valida credenciales, inicia y cierra sesiones.
- `resources/views/auth/login.blade.php`: formulario de ingreso.
- `bootstrap/app.php`: registra los aliases de middleware de Spatie.

### Usuarios y permisos

- `app/Models/User.php`: usuario autenticable y modelo con roles de Spatie.
- `config/permission.php`: configuracion del paquete de roles y permisos.
- `database/seeders/DatabaseSeeder.php`: crea roles, permisos y usuarios demo.
- `resources/views/users/index.blade.php`: consulta de usuarios permitida para `users.manage`.

### Datos industriales

- `Area`: agrupacion principal de la fabrica, por ejemplo Efluentes / PTAR.
- `MeasurementFrequency`: define si una variable se mide por dia, turno u hora.
- `MeasurementVariable`: describe que se mide, en que area, con que unidad y limites opcionales.
- `Measurement`: guarda el valor observado, fecha, hora, turno opcional y responsable.
- `ActivityLog`: estructura preparada para registrar acciones importantes.

### Dashboard

- `app/Http/Controllers/DashboardController.php`: prepara filtros, agregaciones y la serie temporal de la variable seleccionada.
- `resources/views/home.blade.php`: muestra KPIs, tendencia SVG y ultimos registros.

El dashboard consulta MySQL desde el servidor. La vista no inventa datos: recibe la variable, el periodo y las mediciones ya filtradas.

## Recorrido MVC

Una solicitud web sigue este orden:

```text
Navegador
   |
   v
Ruta en routes/web.php
   |
   v
Middleware: guest, auth o permission
   |
   v
Controlador o accion de la ruta
   |
   v
Modelo Eloquent
   |
   v
MySQL
   |
   v
Vista Blade y respuesta HTTP
```

En el dashboard actual, las consultas se realizan desde la accion de la ruta porque el modulo todavia es pequeno. Cuando crezcan las pantallas, esas consultas pasaran a controladores dedicados para mantener cada responsabilidad separada.

## Evolucion prevista

El siguiente corte agregara controladores y vistas para:

- Administrar areas.
- Definir variables y frecuencias.
- Cargar mediciones.
- Consultar historiales.
- Alimentar graficos con consultas reutilizables.

La carpeta `ejemplo/` no forma parte de la arquitectura nueva y no debe usarse como fuente para copiar codigo sin revisarlo.
