<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_log_in_and_access_the_dashboard(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@dataplant.test')->firstOrFail();

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Administrador DataPlant');
    }

    public function test_operario_cannot_access_user_management(): void
    {
        $this->seed();

        $operario = User::where('email', 'operario@dataplant.test')->firstOrFail();

        $this->actingAs($operario)
            ->get(route('users.index'))
            ->assertForbidden();
    }

    public function test_logout_invalidates_the_session(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@dataplant.test')->firstOrFail();

        $this->actingAs($admin);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
