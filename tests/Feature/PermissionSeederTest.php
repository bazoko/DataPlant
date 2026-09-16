<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_initial_roles_and_permissions(): void
    {
        $this->seed();

        $this->assertTrue(Role::where('name', 'admin')->exists());
        $this->assertTrue(Role::where('name', 'operario')->exists());
        $this->assertTrue(Permission::where('name', 'measurements.create')->exists());

        $admin = Role::findByName('admin');

        $this->assertTrue($admin->hasPermissionTo('roles.manage'));
    }
}
