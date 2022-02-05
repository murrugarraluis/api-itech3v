<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Material = Material::create(['name' => 'Camara QHD ZX-77HF','minimum_stock'=>5]);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>5],
            2 => ['quantity' => 3],
        ]);

        $Material = Material::create(['name' => 'Camara HD XD100','minimum_stock'=>5]);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>5],
            2 => ['quantity' => 6],
        ]);

        $Material = Material::create(['name' => 'Camara FULL 360','minimum_stock'=>5]);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>12],
            2 => ['quantity' => 6],
        ]);

        $Material = Material::create(['name' => 'MiniVisor HD','minimum_stock'=>5]);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>5],
            2 => ['quantity' => 8],
        ]);

        $Material = Material::create(['name' => 'CAMARA IP PTZ INT 1080P WIFI C8C','minimum_stock'=>5]);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>2],
            2 => ['quantity' => 1],
        ]);
        $Material = Material::create(['name' => 'CAMARA HK-DS2CE56C0T-IRPF','minimum_stock'=>5]);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>2],
            2 => ['quantity' => 1],
        ]);
        $Material = Material::create(['name' => 'Cámara CS-C6W','minimum_stock'=>5]);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>2],
            2 => ['quantity' => 1],
        ]);
        
        $Material = Material::create(['name' => 'Cámara CS-8899','minimum_stock'=>5]);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>2],
            2 => ['quantity' => 1],
        ]);

        $Material = Material::create(['name' => 'Tinta 3RRE COLOR ROJA','minimum_stock'=>5]);
        $Material->category()->associate(5)->save();
        $Material->mark()->associate(4)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>1],
            2 => ['quantity' => 1],
        ]);
        
        $Material = Material::create(['name' => 'Tinta 3RRE COLOR AMARILLO','minimum_stock'=>5]);
        $Material->category()->associate(5)->save();
        $Material->mark()->associate(4)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>1],
            2 => ['quantity' => 1],
        ]);

        $Material = Material::create(['name' => 'Tinta 3RRE COLOR NEGRO','minimum_stock'=>5]);
        $Material->category()->associate(5)->save();
        $Material->mark()->associate(4)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>2],
            2 => ['quantity' => 3],
        ]);

                
        $Material = Material::create(['name' => 'Guante Protector A12','minimum_stock'=>5]);
        $Material->category()->associate(6)->save();
        $Material->mark()->associate(4)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>6],
            2 => ['quantity' => 4],
        ]);

        $Material = Material::create(['name' => 'Casco Protector XC','minimum_stock'=>5]);
        $Material->category()->associate(7)->save();
        $Material->mark()->associate(4)->save();
        $Material->measure_unit()->associate(1)->save();
        $Material->warehouses()->attach([
            1 => ['quantity' =>6],
            2 => ['quantity' => 4],
        ]);
    }
}
