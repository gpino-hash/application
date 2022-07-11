<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CurrencyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public static function collection($resource)
    {
        return [
            "name" => $resource->name,
            "description" => $resource->description,
            "symbol" => $resource->symbol,
            "code" => $resource->code,
            "decimal_places" => $resource->decimal_places,
            "decimal_separator" => $resource->decimal_separator,
            "thousands_separator" => $resource->thousands_separator,
        ];
    }
}
