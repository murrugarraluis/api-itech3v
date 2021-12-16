<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class WarehouseMaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function convertCode($id): string
    {
        $prefijo = "ALM";
        $nroDigist = 3;
        $digits = '000';
        $number = substr($digits, 0, $nroDigist - strlen($id)) . $id;
        return $prefijo . "-" . $number;
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->convertCode($this->id),
            'name' => $this->name,
            'description' => is_null($this->description) ? 'Sin descripción' : $this->description,
            'quantity'=>$this->pivot? $this->pivot->quantity : '0'
        ];
    }
}
