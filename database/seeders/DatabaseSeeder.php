<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Measurement;
use App\Models\MeasurementFrequency;
use App\Models\MeasurementVariable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'areas.view',
            'areas.manage',
            'measurements.view',
            'measurements.create',
            'dashboard.view',
            'users.manage',
            'roles.manage',
            'audit.view',
        ];

        $permissionModels = collect($permissions)
            ->mapWithKeys(fn (string $permission): array => [
                $permission => Permission::findOrCreate($permission, 'web'),
            ]);

        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->givePermissionTo($permissionModels->values());

        $supervisorRole = Role::findOrCreate('supervisor', 'web');
        $supervisorRole->givePermissionTo($permissionModels->only([
            'areas.view',
            'measurements.view',
            'dashboard.view',
            'audit.view',
        ])->values());

        $operarioRole = Role::findOrCreate('operario', 'web');
        $operarioRole->givePermissionTo($permissionModels->only([
            'areas.view',
            'measurements.view',
            'measurements.create',
            'dashboard.view',
        ])->values());

        $consultaRole = Role::findOrCreate('consulta', 'web');
        $consultaRole->givePermissionTo($permissionModels->only([
            'areas.view',
            'measurements.view',
            'dashboard.view',
        ])->values());

        $admin = User::factory()->create([
            'name' => 'Administrador DataPlant',
            'email' => 'admin@dataplant.test',
            'password' => 'password',
        ]);
        $admin->assignRole($adminRole);

        $operario = User::factory()->create([
            'name' => 'Responsable Turno',
            'email' => 'operario@dataplant.test',
            'password' => 'password',
        ]);
        $operario->assignRole($operarioRole);

        $efluentes = Area::create([
            'name' => 'Efluentes / PTAR',
            'slug' => 'efluentes-ptar',
            'description' => 'Area inicial para seguimiento de variables ambientales y operativas.',
        ]);

        $daily = MeasurementFrequency::create([
            'name' => 'Diaria',
            'code' => 'daily',
            'description' => 'Una medicion por dia.',
            'expected_per_day' => 1,
        ]);

        $perShift = MeasurementFrequency::create([
            'name' => 'Por turno',
            'code' => 'per_shift',
            'description' => 'Una medicion esperada por cada turno de trabajo.',
            'requires_shift' => true,
            'expected_per_day' => 3,
        ]);

        $hourly = MeasurementFrequency::create([
            'name' => 'Horaria',
            'code' => 'hourly',
            'description' => 'Mediciones repetidas durante el dia.',
            'expected_per_day' => 24,
        ]);

        $variables = collect([
            ['frequency' => $hourly, 'name' => 'Caudal entrada arroyo', 'slug' => 'caudal-entrada-arroyo', 'unit' => 'm3/h', 'min_value' => 0, 'max_value' => 120],
            ['frequency' => $hourly, 'name' => 'Salida Parshall', 'slug' => 'salida-parshall', 'unit' => 'm3/h', 'min_value' => 0, 'max_value' => 120],
            ['frequency' => $perShift, 'name' => 'DQO vertido', 'slug' => 'dqo-vertido', 'unit' => 'ppm', 'min_value' => 0, 'max_value' => 900],
            ['frequency' => $perShift, 'name' => 'SST salida', 'slug' => 'sst-salida', 'unit' => 'ppm', 'min_value' => 0, 'max_value' => 500],
            ['frequency' => $daily, 'name' => 'M3/Ton diario', 'slug' => 'm3-ton-diario', 'unit' => 'm3/ton', 'min_value' => 0, 'max_value' => 20],
        ])->map(fn (array $data): MeasurementVariable => MeasurementVariable::create([
            'area_id' => $efluentes->id,
            'measurement_frequency_id' => $data['frequency']->id,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'unit' => $data['unit'],
            'min_value' => $data['min_value'],
            'max_value' => $data['max_value'],
        ]));

        $start = Carbon::create(2026, 9, 1, 8);

        foreach (range(0, 6) as $dayOffset) {
            $date = $start->copy()->addDays($dayOffset);

            foreach ($variables as $variable) {
                $frequency = $variable->frequency->code;

                if ($frequency === 'hourly') {
                    foreach ([6, 14, 22] as $hour) {
                        $this->createMeasurement($variable, $operario, $date->copy()->setTime($hour, 0), null);
                    }

                    continue;
                }

                if ($frequency === 'per_shift') {
                    foreach (['manana', 'tarde', 'noche'] as $index => $shift) {
                        $this->createMeasurement($variable, $operario, $date->copy()->setTime(8 + ($index * 8), 0), $shift);
                    }

                    continue;
                }

                $this->createMeasurement($variable, $admin, $date->copy()->setTime(10, 0), null);
            }
        }
    }

    private function createMeasurement(MeasurementVariable $variable, User $user, Carbon $measuredAt, ?string $shift): void
    {
        $base = match ($variable->slug) {
            'caudal-entrada-arroyo' => 42,
            'salida-parshall' => 38,
            'dqo-vertido' => 420,
            'sst-salida' => 210,
            'm3-ton-diario' => 8,
            default => 10,
        };

        Measurement::create([
            'measurement_variable_id' => $variable->id,
            'user_id' => $user->id,
            'measured_at' => $measuredAt,
            'measured_date' => $measuredAt->toDateString(),
            'shift' => $shift,
            'value' => $base + random_int(-5, 5),
            'observation' => 'Dato ficticio de demo DataPlant.',
        ]);
    }
}
