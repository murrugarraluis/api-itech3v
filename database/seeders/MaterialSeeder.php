<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Material = Material::factory()->create(['name' => 'Camara QHD ZX-77HF']);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();

        $Material = Material::factory()->create(['name' => 'Camara HD XD100']);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();

        $Material = Material::factory()->create(['name' => 'Camara FULL 360']);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();

        $Material = Material::factory()->create(['name' => 'MiniVisor HD']);
        $Material->category()->associate(1)->save();
        $Material->mark()->associate(1)->save();
        $Material->measure_unit()->associate(1)->save();
    }
}
