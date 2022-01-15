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
        $supplier = Supplier::create([
            'type_document'=>'DNI',
            'number_document'=>'75579609',
            'name'=>'Luis',
            'lastname'=>'Murrugarra',
        ]);
    }
}
