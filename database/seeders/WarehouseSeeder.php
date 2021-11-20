<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create(['name'=>'Almacen 01','description'=>'Almacen ubicado en la calle Av.Gonzales Caceda']);
        Warehouse::create(['name'=>'Almacen 02','description'=>'Almacen ubicado en la calle Av.Javier Prado']);
    }
}
