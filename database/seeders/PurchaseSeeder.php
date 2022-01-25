<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Purchase::factory(1000)->create()->each(function ($purchase)
        {
            $purchase->supplier()->associate(rand(1,5))->save();
            $purchase->materials()->attach([
                rand(1,4) => ['quantity' => rand(2,8), 'price' => rand(1,100)],
                rand(1,4) => ['quantity' => rand(2,8), 'price' => rand(1,100)],
            ]);
        });



        // $purchase = Purchase::create([
        //     'way_to_pay' => 'Contado',
        //     'type_document' => 'Boleta',
        //     'number' => 'B001-0001',
        //     'type_purchase' => 'Por Orden de Compra',
        //     'status' => 'Ingresado'
        // ]);
        // $purchase->supplier()->associate(1)->save();
        // $purchase->purchase_order()->associate(1)->save();
        // $purchase->materials()->attach([
        //     1 => ['quantity' => 1, 'price' => 4.5],
        // ]);





    }
}
