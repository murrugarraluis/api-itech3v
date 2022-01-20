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
        $purchase = Purchase::create([
            'way_to_pay' => 'Contado',
            'type_document' => 'Boleta',
            'number' => 'B001-0001',
            'type_purchase' => 'Por Orden de Compra',
            'status' => 'Ingresado'
        ]);
        $purchase->supplier()->associate(1)->save();
        $purchase->purchase_order()->associate(1)->save();
        $purchase->materials()->attach([
            1 => ['quantity' => 1, 'price' => 4.5],
        ]);


        $purchase = Purchase::create([
            'way_to_pay' => 'Contado',
            'type_document' => 'Factura',
            'number' => 'F001-0001',
            'type_purchase' => 'Por Orden de Compra',
            'status' => 'Ingresado'
        ]);
        $purchase->supplier()->associate(3)->save();
        $purchase->purchase_order()->associate(2)->save();
        $purchase->materials()->attach([
            1 => ['quantity' => 2, 'price' => 5],
        ]);

        $purchase = Purchase::create([
            'way_to_pay' => 'Contado',
            'type_document' => 'Factura',
            'number' => 'F001-0003',
            'type_purchase' => 'Por Orden de Compra',
            'status' => 'Ingresado'
        ]);
        $purchase->supplier()->associate(7)->save();
        $purchase->purchase_order()->associate(3)->save();
        $purchase->materials()->attach([
            1 => ['quantity' => 2, 'price' => 2.5],
        ]);
    }
}
