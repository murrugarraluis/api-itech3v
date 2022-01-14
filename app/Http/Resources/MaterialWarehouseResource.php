<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialWarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->convertCode($this->id),
            'name' => $this->name,
            'category' => $this->category->name,
            'mark' => $this->mark->name,
            'measure_unit' => $this->measure_unit->name,
            'minimum_stock' => $this->minimum_stock,
            'stock'=>$this->pivot? $this->pivot->quantity : '0'
        ];
    }
    public function convertCode($id): string
    {
        $prefijo = "MAT";
        $nroDigist = 5;
        $digits = '00000';
        $number = substr($digits, 0, $nroDigist - strlen($id)) . $id;
        return $prefijo . "-" . $number;
    }
}
