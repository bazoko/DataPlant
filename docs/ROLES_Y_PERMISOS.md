# Roles y permisos

DataPlant separa identidad y autorizacion:

- La autenticacion responde quien es la persona.
- El rol agrupa responsabilidades.
- El permiso habilita una accion concreta.

Spatie Laravel Permission guarda esta relacion en tablas propias de MySQL. No se utiliza una columna `role` en `users`.

## Roles iniciales

| Rol | Perfil de uso | Permisos |
| --- | --- | --- |
| `admin` | Administra la aplicacion y su configuracion | Todos los permisos iniciales |
| `supervisor` | Supervisa datos, tableros y auditoria | `areas.view`, `measurements.view`, `dashboard.view`, `audit.view` |
| `operario` | Carga datos de su operacion | `areas.view`, `measurements.view`, `measurements.create`, `dashboard.view` |
| `consulta` | Consulta informacion sin modificarla | `areas.view`, `measurements.view`, `dashboard.view` |

## Permisos iniciales

- `areas.view`: consultar areas.
- `areas.manage`: crear, editar o desactivar areas.
- `measurements.view`: consultar mediciones.
- `measurements.create`: cargar mediciones.
- `dashboard.view`: acceder al dashboard.
- `users.manage`: consultar y administrar usuarios.
- `roles.manage`: administrar roles y permisos.
- `audit.view`: consultar el historial de acciones.

## Decision de acceso

```text
Persona solicita una URL
        |
        v
Tiene sesion?
   |             |
  No             Si
   |             |
 /login     Tiene el permiso?
                  |        |
                 No        Si
                  |        |
                 403    Continua
```

La vista puede ocultar un enlace con `@can`, pero nunca reemplaza al middleware. Un usuario puede escribir una URL manualmente, por eso cada ruta sensible debe verificar su permiso en el servidor.

## Usuarios demo actuales

El seeder crea:

- `admin@dataplant.test` con rol `admin`.
- `operario@dataplant.test` con rol `operario`.

Los roles `supervisor` y `consulta` ya existen y estan preparados para asignarse a nuevos usuarios en el modulo de administracion.

## Proximo incremento

La pantalla actual permite consultar usuarios. El siguiente paso de administracion sera agregar alta, edicion, desactivacion y asignacion de roles, siempre protegido por `users.manage` y `roles.manage`.
