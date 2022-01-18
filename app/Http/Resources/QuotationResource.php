<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Node\Expr\Cast\Double;

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
            'date'=> date_format($this->created_at,"Y-m-d"),
            'code' => $this->convertCode($this->id),
            'supplier'=> $this->supplier->name .' '.$this->supplier->lastname,
            'date_agreed' => $this->date_agreed,
            'way_to_pay' => $this->way_to_pay,
            'type_quotation' => $this->type_quotation,
            'document_number' => $this->document_number,
            'materials' => MaterialQuotationResource::collection($this->materials),
            'status' => $this->status,
            'total_amount'=> $this->totalAmount($this->materials)
        ];
    }
    public function totalAmount($materials)
    {
        $suma = 0;
        foreach ($materials as $material){

            $subTotal = $material->pivot->quantity * $material->pivot->price;
            $suma += $subTotal;
        }
        return $suma;
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
