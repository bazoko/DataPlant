# Documentacion de DataPlant

Esta carpeta explica como esta organizado DataPlant, como se mueve una solicitud por la aplicacion y como una medicion llega desde el proceso operativo hasta MySQL y los tableros.

La documentacion distingue entre:

- **Actual:** comportamiento que ya existe en el codigo.
- **Proximo:** flujo definido para el modulo que vamos a implementar despues.
- **Futuro:** ideas fuera del primer corte, como LILA, alertas avanzadas o integraciones.

## Orden recomendado de lectura

1. [Estructura del proyecto](ESTRUCTURA_PROYECTO.md)
2. [Flujos de la aplicacion](FLUJOS.md)
3. [Recorrido de los datos](RECORRIDO_DATOS.md)
4. [Rutas web](RUTAS.md)
5. [Roles y permisos](ROLES_Y_PERMISOS.md)

## Idea central

DataPlant reemplaza planillas dispersas por un recorrido trazable:

```text
Area -> Variable -> Frecuencia -> Medicion -> MySQL -> Dashboard
```

Cada medicion conserva la variable que fue observada, el momento, el turno cuando corresponde, el valor y el usuario responsable.
