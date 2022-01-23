<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type_request = ['Para Marketing','Para Ventas','Para Contabilidad','Para Gerencia','Para Logistica','Para Almacen'];
        $importance = ['Baja','Media','Alta'];
        $status = ['Confirmado' , 'Pendiente'];
        return [
            'date_required' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'type_request' => $this->faker->randomElement($type_request),
            'importance' => $this->faker->randomElement($importance),
            'comment' => '',
            'status'=>$this->faker->randomElement($status),
            'status_message'=>'Enviado a Logistica',
            // 'user_id'=>$this->faker->numberBetween(1,4)
        ];
    }
}
