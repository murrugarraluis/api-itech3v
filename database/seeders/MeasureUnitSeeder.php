<?php

namespace Database\Seeders;

use App\Models\MeasureUnit;
use Illuminate\Database\Seeder;

class MeasureUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MeasureUnit::create(['name'=>'Cajas']);
        MeasureUnit::create(['name'=>'Kilos']);
    }
}
