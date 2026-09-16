# Diseno de skills para DataPlant

Este documento define skills recomendadas para trabajar el proyecto DataPlant con Codex. No son skills instaladas todavia; son el diseno funcional para crearlas cuando convenga.

## 1. dataplant-product-owner

Descripcion:

Usar cuando se definan alcance, roadmap, prioridades, decisiones de producto, presentacion academica o valor profesional de DataPlant.

Instrucciones clave:

- Mantener la vision industrial: captura de datos operativos, permisos, auditoria y dashboards.
- Priorizar entregables defendibles ante docentes y entendibles para una fabrica.
- Separar alcance actual, futuro y fuera de alcance.
- Evitar convertir DataPlant en una coleccion de formularios sin modelo de negocio.

Uso tipico:

- Definir el alcance de una fase.
- Redactar defensa del proyecto.
- Priorizar si conviene hacer permisos, dashboards, areas o importacion.

## 2. dataplant-laravel-architecture

Descripcion:

Usar cuando se modifique la aplicacion Laravel de DataPlant: modelos, migraciones, rutas, controladores, vistas, seeders, permisos y tests.

Instrucciones clave:

- Revisar el patron existente antes de editar.
- Mantener MVC simple y explicable.
- Agregar migraciones y tests cuando cambie comportamiento.
- Actualizar `docs/` si cambia el flujo.
- No agregar Breeze sin decision explicita.
- Validar `spatie/laravel-permission` antes de implementarlo.

Uso tipico:

- Crear modulo de areas.
- Crear modulo de variables de medicion.
- Migrar roles actuales a permisos profesionales.
- Agregar dashboard Laravel.

## 3. dataplant-data-modeling

Descripcion:

Usar cuando se disenen tablas, relaciones, permisos por area/formulario, frecuencias de medicion, rangos esperados y estructura historica.

Instrucciones clave:

- No dividir area en linea/sector por defecto.
- Modelar frecuencia de medicion explicitamente.
- Permitir datos diarios, por turno, horarios y personalizados.
- Pensar en auditoria, historico y graficos desde el inicio.
- Mantener LILA como futuro salvo pedido explicito.

Uso tipico:

- Decidir si una medicion necesita `turno`, `hora`, `periodo` o `frecuencia`.
- Disenar tablas para Efluentes/PTAR.
- Definir permisos por area y formulario.

## 4. dataplant-dashboard-kpis

Descripcion:

Usar cuando se creen dashboards, graficos, indicadores, filtros, tarjetas resumen y criterios visuales de datos industriales.

Instrucciones clave:

- Los graficos deben responder preguntas operativas.
- Mostrar tendencias, ultimos valores, promedios, faltantes y desvios.
- Evitar graficos decorativos.
- Priorizar filtros por fecha, area, variable, responsable y frecuencia.
- Usar el contexto de Efluentes/PTAR como primer caso real.

Uso tipico:

- Disenar graficos internos en Laravel.
- Traducir logica de Power BI a consultas Laravel.
- Definir KPIs por area.

## 5. dataplant-softys-context

Descripcion:

Usar cuando se necesite recordar el contexto industrial trabajado previamente sobre Softys, sin asumir datos sensibles ni replicar informacion privada.

Instrucciones clave:

- Usar solo contexto conceptual validado en la conversacion/proyecto.
- Areas recordadas: Caldera, Efluentes/PTAR, MP4, TPM, Auditorias, ORP, Center Line.
- Indicadores recordados para Efluentes/PTAR: agua, DQO, SST, O2 reactor, relacion F/M, edad del lodo y humedad.
- LILA existe como posible futuro, pero no entra al alcance actual.
- Evitar usar nombres reales de personas o datos productivos reales.

Uso tipico:

- Elegir variables iniciales para Efluentes.
- Redactar descripcion profesional del caso.
- Armar ejemplos ficticios realistas para seeders o demos.

## Skill inicial recomendada

Si se instala una sola skill primero, conviene crear:

```text
dataplant-laravel-architecture
```

Motivo: es la que mas ayudaria a que los cambios de codigo salgan consistentes con Laravel, el dominio industrial y el estado actual del proyecto.

## Nota sobre instalacion

Estas skills pueden vivir como skills personales en:

```text
C:\Users\Alfredo\.codex\skills
```

Pero para no escribir fuera del repo sin necesidad, primero quedan documentadas aca. Cuando el proyecto tenga estable la direccion, se pueden crear como skills reales.
