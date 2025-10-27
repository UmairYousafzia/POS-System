<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionNames = [
            // Users
            'user.view_all', 'user.view_own', 'user.create', 'user.edit', 'user.delete', 'user.update',
            // Profile
            'profile.edit',
            // Roles
            'role.view', 'role.edit', 'role.create', 'role.delete',
            // Settings
            'settings.users.view', 'settings.users.manage', 'settings.roles.view', 'settings.roles.manage',
            // POS modules (adjust to your policies/middleware as needed)
            'pos.sell', 'pos.purchase',
            'pos.products.view', 'pos.products.manage',
            'pos.parties.view', 'pos.parties.manage',
            'pos.sales.view', 'pos.purchases.view',
            'pos.expenses.view', 'pos.expenses.manage',
            'pos.locations.manage', 'pos.warehouses.manage',
            'pos.stock.view', 'pos.backup.manage',
        ];

        foreach ($permissionNames as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $role = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $role->givePermissionTo($permissionNames);

    }
}
