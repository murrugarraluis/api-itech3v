<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Luis Murrugarra Astolingon',
            'email'=>'admin@app.com',
            'password'=>bcrypt('123456')
        ])->assignRole('logistica');

        User::create([
            'name'=>'Del Piero Urbina Rodriguez',
            'email'=>'logistica@app.com',
            'password'=>bcrypt('123456')
        ]);
        
        User::create([
            'name'=>'Raul Castro Rivera',
            'email'=>'almacen@app.com',
            'password'=>bcrypt('123456')
        ]);
    }
}
