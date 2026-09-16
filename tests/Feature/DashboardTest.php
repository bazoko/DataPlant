<?php

namespace Tests\Feature;

use App\Models\MeasurementVariable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_kpis_and_trend_for_selected_variable(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@dataplant.test')->firstOrFail();
        $variable = MeasurementVariable::where('slug', 'dqo-vertido')->firstOrFail();

        $response = $this->actingAs($admin)->get(route('dashboard', [
            'measurement_variable_id' => $variable->id,
            'date_from' => '2026-09-01',
            'date_to' => '2026-09-07',
        ]));

        $response->assertOk()
            ->assertSee('DQO vertido')
            ->assertSee('Promedio')
            ->assertSee('Fuera de rango')
            ->assertSee('<svg', false)
            ->assertSee('2026-09-01', false);
    }

    public function test_dashboard_respects_the_area_filter_when_selecting_variables(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@dataplant.test')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('dashboard', [
                'area_id' => 1,
                'date_from' => '2026-09-01',
                'date_to' => '2026-09-07',
            ]))
            ->assertOk()
            ->assertSee('Efluentes / PTAR');
    }
}
