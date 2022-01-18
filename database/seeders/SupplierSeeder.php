<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $supplier = Supplier::create([
        //     'type_document'=>'DNI',
        //     'number_document'=>'75579609',
        //     'name'=>'Luis',
        //     'lastname'=>'Murrugarra',
        // ]);
        // $supplier = Supplier::create([
        //     'type_document'=>'RUC',
        //     'number_document'=>'1075579609',
        //     'name'=>'MT Vision S.A.C',
        //     'lastname'=>'',
        // ]);
        // $supplier = Supplier::create([
        //     'type_document'=>'DNI',
        //     'number_document'=>'75579608',
        //     'name'=>'Angel',
        //     'lastname'=>'Sanchez',
        // ]);
        Supplier::factory(100)->create();
    }
}
