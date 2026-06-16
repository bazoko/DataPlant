<?php

namespace Tests\Feature;

use App\Models\Actividad;
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

    public function test_mediciones_can_be_filtered_by_turno(): void
    {
        $consulta = User::factory()->create(['role' => 'consulta']);
        Medicion::create([
            'fecha' => '2026-06-12',
            'turno' => 'manana',
            'valor' => '11.00',
            'observacion' => 'Visible',
            'user_id' => $consulta->id,
        ]);
        Medicion::create([
            'fecha' => '2026-06-12',
            'turno' => 'noche',
            'valor' => '22.00',
            'observacion' => 'Oculta',
            'user_id' => $consulta->id,
        ]);

        $this->actingAs($consulta)
            ->get('/mediciones?turno=manana')
            ->assertOk()
            ->assertSee('Visible')
            ->assertDontSee('Oculta');
    }

    public function test_inspecciones_can_be_filtered_by_estado(): void
    {
        $consulta = User::factory()->create(['role' => 'consulta']);
        Inspeccion::create([
            'fecha' => '2026-06-12',
            'sector' => 'Deposito',
            'estado' => 'correcto',
            'observacion' => 'Visible',
            'user_id' => $consulta->id,
        ]);
        Inspeccion::create([
            'fecha' => '2026-06-12',
            'sector' => 'Planta',
            'estado' => 'critico',
            'observacion' => 'Oculta',
            'user_id' => $consulta->id,
        ]);

        $this->actingAs($consulta)
            ->get('/inspecciones?estado=correcto')
            ->assertOk()
            ->assertSee('Visible')
            ->assertDontSee('Oculta');
    }

    public function test_admin_can_create_and_update_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post('/usuarios', [
                'name' => 'Operador',
                'email' => 'operador@example.com',
                'password' => 'password',
                'role' => 'carga',
            ])
            ->assertRedirect('/usuarios');

        $usuario = User::where('email', 'operador@example.com')->firstOrFail();

        $this->actingAs($admin)
            ->put("/usuarios/{$usuario->id}", [
                'name' => 'Operador Consulta',
                'email' => 'operador@example.com',
                'password' => '',
                'role' => 'consulta',
            ])
            ->assertRedirect('/usuarios');

        $this->assertDatabaseHas('users', [
            'id' => $usuario->id,
            'name' => 'Operador Consulta',
            'role' => 'consulta',
        ]);
    }

    public function test_non_admin_cannot_manage_users(): void
    {
        $carga = User::factory()->create(['role' => 'carga']);

        $this->actingAs($carga)
            ->get('/usuarios')
            ->assertForbidden();

        $this->actingAs($carga)
            ->post('/usuarios', [
                'name' => 'Sin permiso',
                'email' => 'sinpermiso@example.com',
                'password' => 'password',
                'role' => 'consulta',
            ])
            ->assertForbidden();
    }

    public function test_admin_cannot_delete_own_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete("/usuarios/{$admin->id}")
            ->assertRedirect('/usuarios');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_mediciones_export_respects_filters(): void
    {
        $consulta = User::factory()->create(['role' => 'consulta']);
        Medicion::create([
            'fecha' => '2026-06-12',
            'turno' => 'manana',
            'valor' => '11.00',
            'observacion' => 'Exportada',
            'user_id' => $consulta->id,
        ]);
        Medicion::create([
            'fecha' => '2026-06-12',
            'turno' => 'noche',
            'valor' => '22.00',
            'observacion' => 'No exportada',
            'user_id' => $consulta->id,
        ]);

        $response = $this->actingAs($consulta)->get('/mediciones/exportar?turno=manana');
        ob_start();
        $response->baseResponse->sendContent();
        $csv = ob_get_clean();

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=mediciones.csv');
        $this->assertStringContainsString('Exportada', $csv);
        $this->assertStringNotContainsString('No exportada', $csv);
    }

    public function test_inspecciones_export_respects_filters(): void
    {
        $consulta = User::factory()->create(['role' => 'consulta']);
        Inspeccion::create([
            'fecha' => '2026-06-12',
            'sector' => 'Deposito',
            'estado' => 'correcto',
            'observacion' => 'Exportada',
            'user_id' => $consulta->id,
        ]);
        Inspeccion::create([
            'fecha' => '2026-06-12',
            'sector' => 'Planta',
            'estado' => 'critico',
            'observacion' => 'No exportada',
            'user_id' => $consulta->id,
        ]);

        $response = $this->actingAs($consulta)->get('/inspecciones/exportar?estado=correcto');
        ob_start();
        $response->baseResponse->sendContent();
        $csv = ob_get_clean();

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=inspecciones.csv');
        $this->assertStringContainsString('Exportada', $csv);
        $this->assertStringNotContainsString('No exportada', $csv);
    }

    public function test_creating_medicion_registers_activity(): void
    {
        $carga = User::factory()->create(['role' => 'carga']);

        $this->actingAs($carga)
            ->post('/mediciones', [
                'fecha' => '2026-06-16',
                'turno' => 'tarde',
                'valor' => '30.00',
                'observacion' => 'Con auditoria',
            ])
            ->assertRedirect('/mediciones');

        $this->assertDatabaseHas('actividades', [
            'user_id' => $carga->id,
            'modulo' => 'mediciones',
            'accion' => 'crear',
        ]);
    }

    public function test_admin_can_view_activity_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Actividad::create([
            'user_id' => $admin->id,
            'modulo' => 'usuarios',
            'accion' => 'crear',
            'descripcion' => 'Creo un usuario de prueba',
        ]);

        $this->actingAs($admin)
            ->get('/actividad')
            ->assertOk()
            ->assertSee('Creo un usuario de prueba');
    }

    public function test_non_admin_cannot_view_activity_log(): void
    {
        $consulta = User::factory()->create(['role' => 'consulta']);

        $this->actingAs($consulta)
            ->get('/actividad')
            ->assertForbidden();
    }
}
