<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $id
     * @return string
     */
    public function convertCode($id): string
    {
        $prefijo = "ALM";
        $nroDigist= 3;
        $digits = '000';
        $number = substr($digits,0,$nroDigist-strlen($id)).$id;
        return $prefijo."-".$number;
    }
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'code'=>$this->convertCode($this->id),
            'name'=>$this->name,
            'description'=>$this->description
        ];
    }
}
