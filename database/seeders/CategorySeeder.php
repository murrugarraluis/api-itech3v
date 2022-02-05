<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name'=>'Camaras']);
        Category::create(['name'=>'Discos Duros']);
        Category::create(['name'=>'Teclados']);
        Category::create(['name'=>"Usb's"]);
        Category::create(['name'=>"Tintas"]);
        Category::create(['name'=>"Guantes"]);
        Category::create(['name'=>"Cascos"]);

    }
}
