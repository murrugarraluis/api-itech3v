<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type_document'=>'RUC',
            'number_document'=>$this->faker->numberBetween($min = 11111111111, $max = 9999999999),
            'name'=>$this->faker->company,
            'lastname'=>'',
        ];
    }
}
