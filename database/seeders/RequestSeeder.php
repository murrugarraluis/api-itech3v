<?php

namespace Database\Seeders;

use App\Models\Material;
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
        // $Request = Request::create([
        //     'date_required' => '2022-01-05',
        //     'type_request' => 'Para Marketing',
        //     'importance' => 'Media',
        //     'comment' => '',
        //     'status'=>'Pendiente',
        //     'status_message'=>'Enviado a Logistica'
        // ]);
        // $Request->materials()->attach([
        //         1 => ['quantity' =>5],
        //         2 => ['quantity' => 6],
        // ]);

        // $Request->user()->associate(4)->save();
        Request::factory(100)->create()->each(function ($request) {
            $request->user()->associate(rand(1, 4))->save();
            $request->materials()->attach([
                rand(1,4) => ['quantity' => rand(5,15)],
                rand(1,4) => ['quantity' => rand(5,15)],
            ]);
        });
    }
}
