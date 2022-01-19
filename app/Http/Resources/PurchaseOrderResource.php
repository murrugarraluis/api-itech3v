<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
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
            'supplier'=> $this->supplier->id,
            'supplier_fullname'=> $this->supplier->name .''.$this->supplier->lastname,
            'date_required' => $this->date_required,
            'date_agreed' => $this->date_agreed,
            'importance' => $this->importance,
            'type_purchase_order' => $this->type_purchase_order,
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
        $prefijo = "ORD";
        $nroDigist = 6;
        $digits = '000000';
        $number = substr($digits, 0, $nroDigist - strlen($id)) . $id;
        return $prefijo . "-" . $number;
    }
}
