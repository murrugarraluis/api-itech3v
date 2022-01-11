<?php

namespace Database\Seeders;

use App\Models\ExitNote;
use Illuminate\Database\Seeder;

class ExitNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exit_note = ExitNote::create([
            'date' => '2022-01-19',
            'type_exit' => 'Por Requerimiento',
            'comment' => '',
            'document_number'=>'0000001',
        ]);
        $exit_note->materials()->attach([
                1 => ['quantity' =>5],
                2 => ['quantity' => 6],
        ]);

        $exit_note->warehouse()->associate(1)->save();

        $exit_note = ExitNote::create([
            'date' => '2022-01-20',
            'type_exit' => 'Por Requerimiento',
            'comment' => '',
            'document_number'=>'0000002',
        ]);
        $exit_note->materials()->attach([
                1 => ['quantity' =>3],
                2 => ['quantity' => 1],
        ]);

        $exit_note->warehouse()->associate(1)->save();

        $exit_note = ExitNote::create([
            'date' => '2022-01-29',
            'type_exit' => 'Por Requerimiento',
            'comment' => '',
            'document_number'=>'0000003',
        ]);
        $exit_note->materials()->attach([
                1 => ['quantity' =>10],
                2 => ['quantity' => 2],
        ]);

        $exit_note->warehouse()->associate(2)->save();
    }
}
