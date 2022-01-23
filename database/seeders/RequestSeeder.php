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
        Request::factory(1000)->create()->each(function ($request) {
            $request->user()->associate(rand(1, 4))->save();
            $request->materials()->attach([
                rand(1,4) => ['quantity' => rand(5,15)],
                rand(1,4) => ['quantity' => rand(5,15)],
            ]);
        });
    }
}
