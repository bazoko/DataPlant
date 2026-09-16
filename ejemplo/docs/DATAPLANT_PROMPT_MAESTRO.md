# Prompt maestro de DataPlant

## Identidad del proyecto

DataPlant es una aplicacion web Laravel para registrar, organizar y analizar mediciones operativas de una fabrica tipo Softys.

El sistema reemplaza gradualmente planillas Excel dispersas por una base de datos estructurada, con usuarios, permisos, auditoria, formularios por area y graficos para seguimiento operativo.

## Objetivo principal

Construir una herramienta donde responsables de turno o area carguen mediciones de procesos industriales, y donde supervisores puedan consultar historicos, controlar cumplimiento, detectar desvios y visualizar tendencias.

El proyecto debe servir como entrega academica, pero tambien debe verse y explicarse como una solucion profesional demostrable.

## Vision funcional

La aplicacion debe permitir:

- Administrar usuarios, roles y permisos.
- Definir areas de trabajo de fabrica.
- Definir formularios o planillas digitales por area.
- Registrar mediciones con fecha, hora, frecuencia, responsable, valor, unidad y observaciones.
- Consultar, filtrar y exportar informacion.
- Generar graficos operativos desde la informacion cargada.
- Auditar acciones importantes del sistema.

## Decisiones tomadas

- Nombre del sistema: DataPlant.
- Contexto industrial: fabrica tipo Softys.
- Turnos base: manana, tarde y noche.
- No dividir automaticamente area en linea y sector.
- La frecuencia de medicion debe ser flexible: diaria, por turno, por hora o personalizada.
- Primer area real recomendada: Efluentes/PTAR.
- LILA queda como alcance futuro.
- Para permisos profesionales se evaluara `spatie/laravel-permission`.
- No se incorporara Laravel Breeze sobre el login actual salvo decision explicita.

## Modelo conceptual inicial

Entidades probables:

- Usuarios.
- Roles y permisos.
- Areas.
- Formularios.
- Variables de medicion.
- Frecuencias de medicion.
- Mediciones.
- Unidades.
- Rangos esperados o limites.
- Actividad/auditoria.

Ejemplos de areas:

- Efluentes/PTAR.
- Caldera.
- MP4.
- TPM.
- ORP.
- Center Line.
- Auditorias.

Ejemplos de variables:

- Temperatura.
- Presion.
- Caudal.
- M3/Ton.
- DQO.
- SST.
- ORP.
- Humedad.
- Oxigeno.
- Relacion F/M.
- Edad del lodo.
- Produccion.
- Consumo.

## Frecuencia de medicion

No todas las variables se registran igual. El sistema debe contemplar:

- Medicion diaria.
- Medicion por turno.
- Medicion por hora.
- Varias mediciones dentro de un mismo turno.
- Frecuencia personalizada por variable o formulario.

La aplicacion no debe asumir que una medicion siempre equivale a un turno.

## Roles esperados

Roles iniciales sugeridos:

- Administrador: configura usuarios, permisos, areas, formularios y ve todo.
- Supervisor: ve datos de sus areas, dashboards, historicos y puede validar o revisar.
- Operario o responsable de turno: carga datos de los formularios asignados.
- Consulta: solo visualiza informacion permitida.

Los permisos deben poder configurarse por area y formulario.

## Dashboard y graficos

Los graficos dentro de Laravel son un buen plan porque hacen que DataPlant sea una app completa y demostrable sin depender siempre de Power BI.

El enfoque recomendado es:

- Primero cargar datos bien estructurados.
- Luego crear dashboards internos simples y claros.
- Mas adelante mantener exportacion o integracion con Power BI si aporta valor.

Graficos prioritarios:

- Evolucion temporal de una variable.
- Ultimo valor registrado.
- Promedio por dia, turno o rango de fechas.
- Comparacion entre variables de un area.
- Cantidad de mediciones registradas contra esperadas.
- Valores fuera de rango.
- Historial por responsable.

## Primer modulo recomendado: Efluentes/PTAR

Efluentes/PTAR es buen primer modulo real porque ya existe contexto previo con indicadores como:

- Gestion del agua.
- Entrada de arroyo.
- Salida Parshall.
- DQO.
- SST.
- O2 reactor.
- Relacion F/M.
- Edad del lodo.
- Humedad de lodos.
- M3/Ton diario y acumulado.

La version inicial en Laravel no necesita replicar todo Power BI. Debe empezar con una estructura limpia que permita cargar y graficar variables.

## Criterio de calidad

Cada mejora debe ser defendible:

- Que problema industrial resuelve.
- Que dato captura.
- Quien lo carga.
- Quien lo consulta.
- Como se controla el acceso.
- Como se audita.
- Que grafico o indicador se puede construir.

La aplicacion debe crecer con orden, no acumulando pantallas aisladas.
