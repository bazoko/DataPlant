<?php

namespace Database\Seeders;

use App\Models\Inspeccion;
use App\Models\Medicion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Administrador', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Usuario Carga', 'email' => 'carga@example.com', 'role' => 'carga'],
            ['name' => 'Usuario Consulta', 'email' => 'consulta@example.com', 'role' => 'consulta'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], [
                'name' => $user['name'],
                'password' => Hash::make('password'),
                'role' => $user['role'],
            ]);
        }

        $carga = User::where('email', 'carga@example.com')->first();

        $mediciones = [
            ['2026-06-01', 'manana', 12.50, 'Temperatura estable en sector norte'],
            ['2026-06-01', 'tarde', 18.20, 'Aumento leve durante el turno tarde'],
            ['2026-06-02', 'noche', 9.75, 'Lectura nocturna dentro del rango'],
            ['2026-06-03', 'manana', 14.10, 'Control inicial sin observaciones'],
            ['2026-06-04', 'tarde', 21.35, 'Pico registrado cerca del cierre'],
            ['2026-06-05', 'noche', 8.90, 'Valor bajo esperado por horario'],
            ['2026-06-06', 'manana', 16.45, 'Medicion tomada despues de mantenimiento'],
            ['2026-06-07', 'tarde', 19.80, 'Condiciones normales de operacion'],
            ['2026-06-08', 'noche', 10.25, 'Sin variaciones relevantes'],
            ['2026-06-09', 'manana', 13.70, 'Control de rutina'],
            ['2026-06-10', 'tarde', 23.15, 'Valor alto para seguimiento'],
            ['2026-06-11', 'noche', 11.60, 'Lectura estable'],
            ['2026-06-12', 'manana', 15.90, 'Registro posterior a calibracion'],
            ['2026-06-13', 'tarde', 20.40, 'Turno con mayor carga operativa'],
            ['2026-06-14', 'noche', 7.85, 'Valor minimo de la semana'],
            ['2026-06-15', 'manana', 17.25, 'Equipo funcionando correctamente'],
            ['2026-06-16', 'tarde', 22.70, 'Control con observacion menor'],
            ['2026-06-17', 'noche', 12.05, 'Cierre de jornada sin incidentes'],
        ];

        foreach ($mediciones as [$fecha, $turno, $valor, $observacion]) {
            Medicion::updateOrCreate([
                'fecha' => $fecha,
                'turno' => $turno,
                'valor' => $valor,
            ], [
                'observacion' => $observacion,
                'user_id' => $carga->id,
            ]);
        }

        $inspecciones = [
            ['2026-06-01', 'Planta principal', 'correcto', 'Recorrido sin hallazgos'],
            ['2026-06-02', 'Deposito', 'observado', 'Se detecta material fuera de lugar'],
            ['2026-06-03', 'Sala de bombas', 'critico', 'Perdida visible en conexion secundaria'],
            ['2026-06-04', 'Laboratorio', 'correcto', 'Instrumentos ordenados y limpios'],
            ['2026-06-05', 'Patio exterior', 'observado', 'Zona con acumulacion de residuos'],
            ['2026-06-06', 'Oficina tecnica', 'correcto', 'Documentacion disponible'],
            ['2026-06-07', 'Camara fria', 'critico', 'Puerta no cierra correctamente'],
            ['2026-06-08', 'Linea 1', 'correcto', 'Protecciones en buen estado'],
            ['2026-06-09', 'Linea 2', 'observado', 'Ruido inusual en motor'],
            ['2026-06-10', 'Taller', 'correcto', 'Herramientas almacenadas correctamente'],
            ['2026-06-11', 'Vestuario', 'observado', 'Falta reposicion de insumos'],
            ['2026-06-12', 'Comedor', 'correcto', 'Condiciones adecuadas'],
            ['2026-06-13', 'Tablero electrico', 'critico', 'Senalizacion incompleta'],
            ['2026-06-14', 'Entrada de carga', 'observado', 'Demarcacion desgastada'],
            ['2026-06-15', 'Archivo', 'correcto', 'Sector limpio y accesible'],
            ['2026-06-16', 'Azotea', 'observado', 'Canaleta parcialmente obstruida'],
            ['2026-06-17', 'Generador', 'critico', 'Nivel de combustible bajo'],
            ['2026-06-18', 'Recepcion', 'correcto', 'Sin observaciones'],
        ];

        foreach ($inspecciones as [$fecha, $sector, $estado, $observacion]) {
            Inspeccion::updateOrCreate([
                'fecha' => $fecha,
                'sector' => $sector,
            ], [
                'estado' => $estado,
                'observacion' => $observacion,
                'user_id' => $carga->id,
            ]);
        }
    }
}
