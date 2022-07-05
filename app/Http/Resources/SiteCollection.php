<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SiteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public static function collection($resource)
    {
        return [
            "id" => $resource->uuid,
            "name" => $resource->name,
            "branch_office" => $resource->branch_office,
            "site_code" => $resource->symbol,
            "status" => $resource->status,
            "country" => CountryCollection::collection($resource->country),
        ];
    }
}
