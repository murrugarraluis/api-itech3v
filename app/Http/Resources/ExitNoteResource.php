<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExitNoteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->convertCode($this->id),
            'warehouse' => $this->warehouse ? $this->warehouse->id : 'None',
            'date' => $this->date,
            'type_exit' => $this->type_exit,
            'comment' => $this->comment,
            'document_number' => $this->document_number,
            'materials' => MaterialRequestResource::collection($this->materials),
        ];
    }
    public function convertCode($id): string
    {
        $prefijo = "NSA";
        $nroDigist = 6;
        $digits = '000000';
        $number = substr($digits, 0, $nroDigist - strlen($id)) . $id;
        return $prefijo . "-" . $number;
    }
}
