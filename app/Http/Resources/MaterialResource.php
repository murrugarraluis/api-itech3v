<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class MaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
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
            'warehouses' => WarehouseResource::collection($this->warehouses),
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
