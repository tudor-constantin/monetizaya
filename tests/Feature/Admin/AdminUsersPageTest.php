<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class AdminUsersPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_users_page_can_be_rendered(): void
    {
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('creator', 'web');

        $admin = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.users'));

        $response
            ->assertOk()
            ->assertSeeVolt('admin.users');
    }
}
