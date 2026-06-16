<?php

namespace Tests\Feature;

use App\Models\Inspeccion;
use App\Models\Medicion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_available(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_home_requires_authentication(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_create_forms(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/mediciones/create')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/inspecciones/create')
            ->assertOk();
    }

    public function test_consulta_cannot_access_create_forms(): void
    {
        $consulta = User::factory()->create(['role' => 'consulta']);

        $this->actingAs($consulta)
            ->get('/mediciones/create')
            ->assertForbidden();

        $this->actingAs($consulta)
            ->get('/inspecciones/create')
            ->assertForbidden();
    }

    public function test_carga_can_store_medicion(): void
    {
        $carga = User::factory()->create(['role' => 'carga']);

        $this->actingAs($carga)
            ->post('/mediciones', [
                'fecha' => '2026-06-12',
                'turno' => 'manana',
                'valor' => '15.25',
                'observacion' => 'Registro de prueba',
            ])
            ->assertRedirect('/mediciones');

        $this->assertDatabaseHas('mediciones', [
            'turno' => 'manana',
            'observacion' => 'Registro de prueba',
            'user_id' => $carga->id,
        ]);
    }

    public function test_carga_can_store_inspeccion(): void
    {
        $carga = User::factory()->create(['role' => 'carga']);

        $this->actingAs($carga)
            ->post('/inspecciones', [
                'fecha' => '2026-06-12',
                'sector' => 'Deposito',
                'estado' => 'correcto',
                'observacion' => 'Sin novedades',
            ])
            ->assertRedirect('/inspecciones');

        $this->assertDatabaseHas('inspecciones', [
            'sector' => 'Deposito',
            'estado' => 'correcto',
            'user_id' => $carga->id,
        ]);
    }

    public function test_admin_can_update_and_delete_medicion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $medicion = Medicion::create([
            'fecha' => '2026-06-12',
            'turno' => 'tarde',
            'valor' => '10.00',
            'observacion' => 'Original',
            'user_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->put("/mediciones/{$medicion->id}", [
                'fecha' => '2026-06-13',
                'turno' => 'noche',
                'valor' => '22.50',
                'observacion' => 'Actualizada',
            ])
            ->assertRedirect('/mediciones');

        $this->assertDatabaseHas('mediciones', [
            'id' => $medicion->id,
            'turno' => 'noche',
            'observacion' => 'Actualizada',
        ]);

        $this->actingAs($admin)
            ->delete("/mediciones/{$medicion->id}")
            ->assertRedirect('/mediciones');

        $this->assertDatabaseMissing('mediciones', ['id' => $medicion->id]);
    }

    public function test_carga_cannot_update_or_delete_medicion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $carga = User::factory()->create(['role' => 'carga']);
        $medicion = Medicion::create([
            'fecha' => '2026-06-12',
            'turno' => 'tarde',
            'valor' => '10.00',
            'observacion' => 'Original',
            'user_id' => $admin->id,
        ]);

        $this->actingAs($carga)
            ->get("/mediciones/{$medicion->id}/edit")
            ->assertForbidden();

        $this->actingAs($carga)
            ->delete("/mediciones/{$medicion->id}")
            ->assertForbidden();
    }

    public function test_admin_can_update_and_delete_inspeccion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $inspeccion = Inspeccion::create([
            'fecha' => '2026-06-12',
            'sector' => 'Planta',
            'estado' => 'observado',
            'observacion' => 'Original',
            'user_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->put("/inspecciones/{$inspeccion->id}", [
                'fecha' => '2026-06-13',
                'sector' => 'Deposito',
                'estado' => 'correcto',
                'observacion' => 'Actualizada',
            ])
            ->assertRedirect('/inspecciones');

        $this->assertDatabaseHas('inspecciones', [
            'id' => $inspeccion->id,
            'sector' => 'Deposito',
            'estado' => 'correcto',
        ]);

        $this->actingAs($admin)
            ->delete("/inspecciones/{$inspeccion->id}")
            ->assertRedirect('/inspecciones');

        $this->assertDatabaseMissing('inspecciones', ['id' => $inspeccion->id]);
    }
}
