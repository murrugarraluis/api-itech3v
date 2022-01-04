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
        $logistics = Role::create(['name' => 'logistics']);
        $marketing = Role::create(['name' => 'marketing']);
        $warehouse = Role::create(['name' => 'warehouse']);

        Permission::create(['name' => 'view materials'])->assignRole($logistics);
        Permission::create(['name' => 'view categories'])->assignRole($logistics);
        Permission::create(['name' => 'view marks'])->assignRole($logistics);
        Permission::create(['name' => 'view measure units'])->assignRole($logistics);
        Permission::create(['name' => 'view suppliers'])->assignRole($logistics);
        Permission::create(['name' => 'view warehouses'])->assignRole($logistics);
        Permission::create(['name' => 'view purchases'])->assignRole($logistics);
        Permission::create(['name' => 'view orders purchase'])->assignRole($logistics);
        Permission::create(['name' => 'view quotes'])->assignRole($logistics);
        Permission::create(['name' => 'view entry note'])->assignRole($warehouse);
        Permission::create(['name' => 'view exit note'])->assignRole($warehouse);
        Permission::create(['name' => 'view requests'])->syncRoles($logistics,$marketing,$warehouse);
    }
}
