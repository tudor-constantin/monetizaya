<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage users',
            'manage creators',
            'manage content',
            'manage subscriptions',
            'manage payments',
            'manage platform settings',
            'view analytics',
            'review reported content',
            'publish posts',
            'publish resources',
            'publish courses',
            'view own analytics',
            'manage own content',
            'subscribe to creators',
            'access premium content',
            'manage own subscriptions',
            'download invoices',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $creatorRole = Role::firstOrCreate(['name' => 'creator']);
        $creatorRole->givePermissionTo([
            'view dashboard',
            'publish posts',
            'publish resources',
            'publish courses',
            'view own analytics',
            'manage own content',
            'manage own subscriptions',
            'download invoices',
        ]);

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo([
            'view dashboard',
            'subscribe to creators',
            'access premium content',
            'manage own subscriptions',
            'download invoices',
        ]);
    }
}
