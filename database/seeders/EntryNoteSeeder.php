<?php

namespace Database\Seeders;

use App\Models\EntryNote;
use Illuminate\Database\Seeder;

class EntryNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exit_note = EntryNote::create([
            'date' => '2022-01-19',
            'type_entry' => 'Por Compra',
            'comment' => '',
            'document_number'=>'0000001',
        ]);
        $exit_note->materials()->attach([
                1 => ['quantity' =>5],
                2 => ['quantity' => 6],
        ]);
        $exit_note->warehouse()->associate(1)->save();

        $exit_note = EntryNote::create([
            'date' => '2022-01-19',
            'type_entry' => 'Por Compra',
            'comment' => '',
            'document_number'=>'0000001',
        ]);
        $exit_note->materials()->attach([
                1 => ['quantity' =>5],
                2 => ['quantity' => 6],
        ]);
        $exit_note->warehouse()->associate(1)->save();

        $exit_note = EntryNote::create([
            'date' => '2022-01-19',
            'type_entry' => 'Por Reeingreso',
            'comment' => '',
            'document_number'=>'0000001',
        ]);
        $exit_note->materials()->attach([
                1 => ['quantity' =>5],
                2 => ['quantity' => 6],
        ]);
        $exit_note->warehouse()->associate(2)->save();
    }
}
