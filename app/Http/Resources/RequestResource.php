<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class RequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->convertCode($this->id),
            'date_required' => $this->date_required,
            'type_request' => $this->type_request,
            'importance' => $this->importance,
            'comment' => $this->comment,
            'materials' => MaterialResource::collection($this->materials),
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function convertCode($id): string
    {
        $prefijo = "REQ";
        $nroDigist = 6;
        $digits = '000000';
        $number = substr($digits, 0, $nroDigist - strlen($id)) . $id;
        return $prefijo . "-" . $number;
    }
}
