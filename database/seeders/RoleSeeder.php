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
        Permission::create(['name' => 'create material'])->assignRole($logistica);
        Permission::create(['name' => 'edit material'])->assignRole($logistica);
        Permission::create(['name' => 'update material'])->assignRole($logistica);
        Permission::create(['name' => 'delete material'])->assignRole($logistica);
    }
}
