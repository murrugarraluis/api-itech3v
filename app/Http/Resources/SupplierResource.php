<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'type_document' => $this->type_document,
            'number_document' => $this->number_document,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'fullname' => $this->name . ' ' . $this->lastname
        ];
    }
    public function convertCode($id): string
    {
        $prefijo = "PRO";
        $nroDigist = 4;
        $digits = '0000';
        $number = substr($digits, 0, $nroDigist - strlen($id)) . $id;
        return $prefijo . "-" . $number;
    }
}
