<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MarkSeeder::class);
        $this->call(MeasureUnitSeeder::class);
        $this->call(MaterialSeeder::class);
        $this->call(RequestSeeder::class);

    }
}
