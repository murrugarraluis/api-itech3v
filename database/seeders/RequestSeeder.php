<?php

namespace Database\Seeders;

use App\Models\Request;
use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Request = Request::create([
            'date_required' => '2022-01-05',
            'type_request' => 'Para Marketing',
            'importance' => 'Media',
            'comment' => '',
        ]);
        $Request->materials()->attach([
                1 => ['quantity' =>5],
                2 => ['quantity' => 6],
        ]);

        $Request->user()->associate(4)->save();

        $Request = Request::create([
            'date_required' => '2022-01-02',
            'type_request' => 'Para Marketing',
            'importance' => 'Alta',
            'comment' => '',
        ]);


        $Request->materials()->attach([
            1 => ['quantity' =>10],
            2 => ['quantity' => 4],
        ]);

        $Request->user()->associate(4)->save();

        $Request = Request::create([
            'date_required' => '2022-01-25',
            'type_request' => 'Para Marketing',
            'importance' => 'Baja',
            'comment' => '',
        ]);
        $Request->materials()->attach([
            1 => ['quantity' =>2],
            2 => ['quantity' => 4],
            3 => ['quantity' =>1],
            4 => ['quantity' => 4],
        ]);

        $Request->user()->associate(1)->save();
    }
}
