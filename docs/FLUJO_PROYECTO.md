# Flujo general del proyecto

## Objetivo del sistema

Permitir el ingreso y consulta de datos mediante formularios web hechos con Laravel MVC.

## Flujo de autenticacion

1. El usuario entra a `/login`.
2. Completa email y contrasena.
3. `AuthController@login` valida los datos.
4. Laravel inicia la sesion.
5. El usuario es redirigido a `/`.
6. La navegacion se adapta al rol del usuario.

Usuarios de prueba:

- `admin@example.com` / `password`
- `carga@example.com` / `password`
- `consulta@example.com` / `password`

## Flujo de carga de datos

1. El usuario con rol `admin` o `carga` entra a un formulario.
2. Laravel muestra la vista Blade correspondiente.
3. El usuario completa los datos.
4. El formulario envia una solicitud `POST`.
5. La ruta envia la solicitud al controlador.
6. El controlador valida los datos.
7. El modelo guarda el registro en la base de datos.
8. El sistema redirige al listado.

## Flujo de consulta de datos

1. El usuario entra al listado de mediciones o inspecciones.
2. Puede completar filtros opcionales.
3. El navegador envia una solicitud `GET` con parametros.
4. El controlador aplica los filtros sobre el modelo.
5. La vista muestra solo los registros encontrados.

## Flujo del dashboard

1. El usuario ingresa al inicio.
2. `HomeController` consulta totales de mediciones e inspecciones.
3. Tambien obtiene los ultimos registros cargados.
4. La vista `home.blade.php` muestra un resumen general del sistema.

## Flujo interno MVC

```txt
Usuario
Vista Blade
Ruta
Controlador
Modelo
Base de datos
Modelo
Controlador
Vista Blade
Usuario
```

## Flujo por roles

### Admin

Puede:

- Ver datos.
- Crear datos.
- Editar datos.
- Eliminar datos.
- Acceder a todos los modulos actuales.
- Administrar usuarios y roles.

### Carga

Puede:

- Ver datos.
- Crear mediciones.
- Crear inspecciones.

No puede:

- Administrar usuarios.
- Editar registros.
- Eliminar registros.

## Flujo de administracion de usuarios

1. El usuario `admin` entra a la seccion Usuarios.
2. Puede crear usuarios nuevos con nombre, email, contrasena y rol.
3. Puede editar datos y cambiar roles.
4. Puede eliminar usuarios.
5. El sistema evita que el admin elimine su propio usuario.

### Consulta

Puede:

- Ver mediciones.
- Ver inspecciones.

No puede:

- Crear registros.
- Editar registros.
- Eliminar registros.
