# Recorrido de los datos

El dato industrial no aparece directamente en un grafico. Primero debe tener contexto, responsable y una frecuencia que permita compararlo con otros registros.

## Cadena de informacion

```text
1. Area
   Efluentes / PTAR
        |
2. Variable
   DQO vertido, SST salida, Caudal entrada arroyo...
        |
3. Frecuencia
   Diaria, por turno, horaria o personalizada
        |
4. Captura
   Valor + fecha/hora + turno opcional + observacion + usuario
        |
5. Persistencia
   Registro en measurements relacionado con MySQL
        |
6. Consulta
   Filtros, totales, promedios y series temporales
        |
7. Visualizacion
   Tablas, indicadores y grafico de tendencia del dashboard
```

## Entidades y relaciones

| Entidad | Que representa | Relacion principal |
| --- | --- | --- |
| `areas` | Una agrupacion operativa de la fabrica | Tiene muchas variables |
| `measurement_frequencies` | La periodicidad esperada | Tiene muchas variables |
| `measurement_variables` | El indicador que se mide y su unidad | Pertenece a un area y una frecuencia |
| `measurements` | El valor observado en un momento concreto | Pertenece a una variable y a un usuario |
| `users` | La persona que carga o consulta | Tiene mediciones y logs |
| `activity_logs` | Una accion trazable del sistema | Puede pertenecer a un usuario |

## Que guarda una medicion

Una fila de `measurements` contiene:

- `measurement_variable_id`: que variable fue medida.
- `user_id`: quien registro el dato, opcional si en el futuro ingresa un proceso automatico.
- `measured_at`: momento exacto de la medicion.
- `measured_date`: fecha separada para filtros y agrupaciones diarias eficientes.
- `shift`: `manana`, `tarde`, `noche` o vacio si la variable no es por turno.
- `value`: valor numerico con precision industrial.
- `observation`: contexto adicional escrito por el responsable.
- `status`: estado del registro, inicialmente `recorded`.

## Ejemplo de recorrido

Una persona del turno tarde registra `DQO vertido = 420 ppm`:

1. El sistema identifica la variable `dqo-vertido`.
2. Esa variable pertenece al area Efluentes / PTAR.
3. Su frecuencia indica que requiere turno.
4. Se valida que el turno sea `tarde` y que el valor sea numerico.
5. Se guarda el valor junto al usuario y el momento exacto.
6. Una consulta puede agruparlo por dia, turno o variable.
7. Un grafico futuro puede mostrar la evolucion de DQO y compararla con sus limites.

## Por que el turno es opcional

No todas las variables se miden de la misma manera. Una variable diaria no debe recibir un turno inventado y una variable horaria necesita conservar la hora real. Por eso `shift` acepta valor nulo y la frecuencia indica las reglas de captura.

## Reglas que se aplican en la carga actual

El modulo de mediciones debera validar:

- La variable debe existir y estar activa.
- El valor debe ser numerico.
- La fecha y hora deben ser validas.
- El turno es obligatorio cuando la frecuencia lo requiere.
- Los limites configurados marcan el estado como `recorded` u `out_of_range`.

Los limites ya existen como `min_value` y `max_value` en la variable. La deteccion de duplicados por frecuencia, los controles de consistencia entre area y variable y las alertas quedan para una etapa posterior.

## Reglas del dashboard actual y futuros graficos

El dashboard actual ya cumple estas condiciones para la serie seleccionada:

- Variable y unidad.
- Periodo consultado.
- Area seleccionada.
- Frecuencia o agrupacion utilizada.
- Cantidad de registros incluidos.
- Si los valores estan dentro o fuera de los limites, cuando esa regla este implementada.

Asi el dashboard sirve para tomar decisiones y no solo para mostrar numeros.
