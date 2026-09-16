<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Measurement;
use App\Models\MeasurementVariable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeasurementTest extends TestCase
{
    use RefreshDatabase;

    public function test_operario_can_register_a_measurement_and_audit_event(): void
    {
        $this->seed();

        $operario = User::where('email', 'operario@dataplant.test')->firstOrFail();
        $variable = MeasurementVariable::where('slug', 'dqo-vertido')->firstOrFail();

        $response = $this->actingAs($operario)->post(route('measurements.store'), [
            'measurement_variable_id' => $variable->id,
            'measured_date' => '2026-09-16',
            'measured_time' => '16:30',
            'shift' => 'tarde',
            'value' => '450.25',
            'observation' => 'Carga de prueba.',
        ]);

        $response->assertRedirect(route('measurements.index'));
        $measurement = Measurement::query()
            ->where('measurement_variable_id', $variable->id)
            ->where('user_id', $operario->id)
            ->whereDate('measured_date', '2026-09-16')
            ->where('shift', 'tarde')
            ->latest('id')
            ->first();

        $this->assertNotNull($measurement);
        $this->assertSame('450.2500', (string) $measurement->value);
        $this->assertSame('recorded', $measurement->status);
        $this->assertTrue(ActivityLog::where('module', 'measurements')->where('action', 'created')->exists());
    }

    public function test_measurement_outside_limits_is_marked(): void
    {
        $this->seed();

        $operario = User::where('email', 'operario@dataplant.test')->firstOrFail();
        $variable = MeasurementVariable::where('slug', 'dqo-vertido')->firstOrFail();

        $this->actingAs($operario)->post(route('measurements.store'), [
            'measurement_variable_id' => $variable->id,
            'measured_date' => '2026-09-20',
            'measured_time' => '16:30',
            'shift' => 'tarde',
            'value' => '950',
        ])->assertRedirect(route('measurements.index'));

        $this->assertTrue(Measurement::query()
            ->where('measurement_variable_id', $variable->id)
            ->whereDate('measured_date', '2026-09-20')
            ->where('status', 'out_of_range')
            ->exists());
    }

    public function test_shift_is_required_for_per_shift_variables(): void
    {
        $this->seed();

        $operario = User::where('email', 'operario@dataplant.test')->firstOrFail();
        $variable = MeasurementVariable::where('slug', 'dqo-vertido')->firstOrFail();

        $this->actingAs($operario)
            ->from(route('measurements.create'))
            ->post(route('measurements.store'), [
                'measurement_variable_id' => $variable->id,
                'measured_date' => '2026-09-21',
                'measured_time' => '16:30',
                'value' => '450',
            ])
            ->assertRedirect(route('measurements.create'))
            ->assertSessionHasErrors('shift');

        $this->assertFalse(Measurement::whereDate('measured_date', '2026-09-21')->exists());
    }

    public function test_consulta_cannot_open_measurement_form(): void
    {
        $this->seed();

        $consulta = User::factory()->create(['email' => 'consulta@dataplant.test']);
        $consulta->assignRole('consulta');

        $this->actingAs($consulta)
            ->get(route('measurements.create'))
            ->assertForbidden();
    }
}
