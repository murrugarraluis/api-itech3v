<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'date' => date_format($this->created_at, "Y-m-d"),
            'date_required' => $this->date_required,
            'code' => $this->convertCode($this->id),
            'supplier' => $this->supplier->id,
            'supplier_fullname' => $this->supplier->name . '' . $this->supplier->lastname,
            'way_to_pay' => $this->way_to_pay,
            'type_document' => $this->type_document,
            'number' => $this->number,
            'type_purchase' => $this->type_purchase,
            'document_number' => $this->purchase_order ? $this->purchase_order->id : '',
            'materials' => MaterialQuotationResource::collection($this->materials),
            'status' => $this->status,
            'total_amount' => $this->totalAmount($this->materials)
        ];
    }
    public function totalAmount($materials)
    {
        $suma = 0;
        foreach ($materials as $material) {

            $subTotal = $material->pivot->quantity * $material->pivot->price;
            $suma += $subTotal;
        }
        return $suma;
    }
    public function convertCode($id): string
    {
        $prefijo = "COM";
        $nroDigist = 6;
        $digits = '000000';
        $number = substr($digits, 0, $nroDigist - strlen($id)) . $id;
        return $prefijo . "-" . $number;
    }
}
