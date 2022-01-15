<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
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
            'supplier'=> $this->supplier->name .' '.$this->supplier->lastname,
            'date_agreed' => $this->date_agreed,
            'way_to_pay' => $this->way_to_pay,
            'type_quotation' => $this->type_quotation,
            'document_number' => $this->document_number,
            'materials' => MaterialRequestResource::collection($this->materials),
        ];
    }
    public function convertCode($id): string
    {
        $prefijo = "COT";
        $nroDigist = 6;
        $digits = '000000';
        $number = substr($digits, 0, $nroDigist - strlen($id)) . $id;
        return $prefijo . "-" . $number;
    }
}
