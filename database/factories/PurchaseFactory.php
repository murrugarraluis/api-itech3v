<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $way_to_pay = ['Contado' , 'Credito'];
        return [
            'date_required' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'way_to_pay' => $this->faker->randomElement($way_to_pay),
            'type_document' => 'Factura',
            'number' => 'F001-'.$this->faker->numberBetween($min = 11111111111, $max = 9999999999),
            'type_purchase' => 'Por Orden de Compra',
            'status' => 'Ingresado'
        ];
    }
}
