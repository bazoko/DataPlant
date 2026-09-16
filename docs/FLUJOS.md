# Flujos de la aplicacion

Este documento describe que ocurre desde que una persona entra al sistema hasta que recibe una respuesta.

## Flujo de entrada

Actualmente `/` funciona como punto de entrada y redirige a `/dashboard`.

```text
GET /
  -> redireccion a /dashboard
  -> middleware auth
  -> si no hay sesion: redireccion a /login
  -> si hay sesion: middleware permission:dashboard.view
  -> dashboard
```

Esto permite que el sistema tenga una unica puerta de entrada y que ninguna consulta operativa quede expuesta a visitantes sin autenticar.

## Flujo de autenticacion

```text
GET /login
  -> AuthController::create()
  -> resources/views/auth/login.blade.php

POST /login
  -> valida email y contrasena
  -> Auth::attempt()
  -> regenera la sesion
  -> redirige al dashboard o a la URL que la persona queria abrir

POST /logout
  -> Auth::logout()
  -> invalida la sesion
  -> regenera el token CSRF
  -> vuelve a /login
```

Si las credenciales no son validas, el formulario conserva el correo y muestra un error sin iniciar sesion.

## Flujo de autorizacion

Autenticar identifica a la persona. Autorizar decide que puede hacer.

```text
Solicitud autenticada
  -> middleware permission:permiso.requerido
  -> permiso concedido: continua la solicitud
  -> permiso ausente: respuesta HTTP 403
```

La interfaz tambien utiliza `@can` para ocultar enlaces que no corresponden, pero la proteccion real esta en el middleware de la ruta.

## Flujo actual del dashboard

```text
GET /dashboard
  -> auth
  -> permission:dashboard.view
  -> valida area, variable y periodo
  -> calcula ultimo valor, promedio, minimo, maximo y fuera de rango
  -> obtiene la serie temporal y los 8 registros mas recientes
  -> envia los datos a home.blade.php
  -> muestra KPIs, grafico SVG y tabla operativa
```

El dashboard actual es una primera vista analitica. Usa la variable seleccionada como serie y conserva la unidad, el area y el periodo para que la lectura sea verificable.

## Flujo actual de usuarios

```text
GET /usuarios
  -> auth
  -> permission:users.manage
  -> consulta usuarios con sus roles
  -> muestra users/index.blade.php
```

En este corte la pantalla es de consulta. El alta, edicion, desactivacion y cambio de roles forman parte del siguiente incremento.

## Flujo actual: carga de una medicion

Este es el flujo implementado para Efluentes / PTAR:

```text
Operario abre el formulario
  -> elige area y variable
  -> el sistema muestra unidad y frecuencia
  -> ingresa valor, fecha, hora, turno opcional y observacion
  -> Laravel valida la combinacion y la frecuencia
  -> calcula si el valor esta dentro de los limites
  -> se guarda la medicion con el usuario autenticado
  -> se registra la accion de auditoria
  -> se redirige al historial
  -> el dashboard puede incluir el nuevo dato
```

La frecuencia decide la forma del formulario:

- Diaria: una medicion esperada por dia, sin turno obligatorio.
- Por turno: una medicion por `manana`, `tarde` o `noche`.
- Horaria: varias mediciones en el mismo dia, usando fecha y hora.
- Personalizada: se habilitara cuando tengamos una necesidad concreta.

## Flujo de auditoria

La tabla `activity_logs` ya esta preparada y la carga de mediciones registra automaticamente la accion, el usuario, el modulo, el identificador de la medicion y su estado.

```text
Accion importante
  -> modulo y accion
  -> usuario autenticado
  -> descripcion y metadata
  -> activity_logs
```
