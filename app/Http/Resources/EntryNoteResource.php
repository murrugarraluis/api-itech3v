<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntryNoteResource extends JsonResource
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
            'warehouse' => $this->warehouse ? $this->warehouse->id : 'None',
            'date' => $this->date,
            'type_entry' => $this->type_entry,
            'comment' => $this->comment,
            'document_number' => $this->document_number,
            'materials' => MaterialRequestResource::collection($this->materials),
        ];
    }
    public function convertCode($id): string
    {
        $prefijo = "NIN";
        $nroDigist = 6;
        $digits = '000000';
        $number = substr($digits, 0, $nroDigist - strlen($id)) . $id;
        return $prefijo . "-" . $number;
    }
}
