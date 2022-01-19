<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchaseOrder = PurchaseOrder::create([
            'date_required' => '2022-01-05',
            'date_agreed' => '2022-01-05',
            'importance' => 'Media',
            'type_purchase_order' => 'Por Cotizacion',
            'document_number' => '0000011',
            'status'=>'Usado'
        ]);
        $purchaseOrder->materials()->attach([
            1 => ['quantity' => 5, 'price' => 1.5],
        ]);
        $purchaseOrder->supplier()->associate(1)->save();

        $purchaseOrder = PurchaseOrder::create([
            'date_required' => '2022-01-15',
            'date_agreed' => '2022-01-18',
            'importance' => 'Media',
            'type_purchase_order' => 'Por Cotizacion',
            'document_number' => '0000012',
            'status'=>'Usado'
        ]);
        $purchaseOrder->materials()->attach([
            1 => ['quantity' => 7, 'price' => 2.5],
        ]);
        $purchaseOrder->supplier()->associate(5)->save();

        $purchaseOrder = PurchaseOrder::create([
            'date_required' => '2022-01-25',
            'date_agreed' => '2022-01-30',
            'importance' => 'Media',
            'type_purchase_order' => 'Por Cotizacion',
            'document_number' => '0000026',
            'status'=>'Usado'
        ]);
        $purchaseOrder->materials()->attach([
            1 => ['quantity' => 10, 'price' => 8.5],
        ]);
        $purchaseOrder->supplier()->associate(4)->save();
    }
}
