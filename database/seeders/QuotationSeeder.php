<?php

namespace Database\Seeders;

use App\Models\Quotation;
use Illuminate\Database\Seeder;

class QuotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quotation = Quotation::create([
            'date_agreed' => '2022-01-05',
            'way_to_pay' => 'Contado',
            'type_quotation' => 'Por Requerimiento',
            'document_number' => '0000011',
            'status' => 'Usado'
        ]);
        $quotation->supplier()->associate(1)->save();
        $quotation->materials()->attach([
            1 => ['quantity' => 15, 'price' => 1.99],
        ]);

        $quotation = Quotation::create([
            'date_agreed' => '2022-01-05',
            'way_to_pay' => 'Contado',
            'type_quotation' => 'Por Requerimiento',
            'document_number' => '00000AA1',
            'status' => 'Usado'
        ]);
        $quotation->supplier()->associate(1)->save();
        $quotation->materials()->attach([
            1 => ['quantity' => 10, 'price' => 2.30],
        ]);

        $quotation = Quotation::create([
            'date_agreed' => '2022-01-05',
            'way_to_pay' => 'Contado',
            'type_quotation' => 'Por Requerimiento',
            'document_number' => 'ABV000011',
            'status' => 'Usado'
        ]);
        $quotation->supplier()->associate(1)->save();
        $quotation->materials()->attach([
            1 => ['quantity' => 1, 'price' => 15.5],
        ]);
    }
}
