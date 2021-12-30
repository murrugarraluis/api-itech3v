<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $logistica = Role::create(['name' => 'logistica']);
        $marketing = Role::create(['name' => 'marketing']);

        Permission::create(['name' => 'view materials'])->assignRole($logistica);
        Permission::create(['name' => 'view categories'])->assignRole($logistica);
        Permission::create(['name' => 'view marks'])->assignRole($logistica);
        Permission::create(['name' => 'view measure units'])->assignRole($logistica);
        Permission::create(['name' => 'view suppliers'])->assignRole($logistica);
        Permission::create(['name' => 'view warehouses'])->assignRole($logistica);
        Permission::create(['name' => 'view purchases'])->assignRole($logistica);
        Permission::create(['name' => 'view orders purchase'])->assignRole($logistica);
        Permission::create(['name' => 'view quotes'])->assignRole($logistica);
        Permission::create(['name' => 'view requests'])->syncRoles($logistica,$marketing);
    }
}
