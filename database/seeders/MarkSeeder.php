<?php

namespace Database\Seeders;

use App\Models\Mark;
use Illuminate\Database\Seeder;

class MarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mark::create(['name'=>'ezviz']);
        Mark::create(['name'=>'kingston']);
        Mark::create(['name'=>'Lenovo']);
        Mark::create(['name'=>'Epson']);
        Mark::create(['name'=>'Constructor']);

    }
}
