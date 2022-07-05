<?php

namespace App\Http\Resources;

use Carbon\Carbon;

class ProductCollection extends AbstractResourceCollection
{

    private array $data;

    /**
     * @return array
     */
    protected function getData(): array
    {
        foreach ($this->collection as $collection) {
            $this->data[] = [
                "id" => $collection->uuid,
                "title" => $collection->title,
                "description" => $collection->description,
                "amount" => $collection->stock,
                "price" => $collection->price,
                "status" => $collection->status,
                "created_at" => Carbon::parse($collection->created_at)->format("d-m-Y"),
                "site" => SiteCollection::collection($collection->site),

            ];
        }
        return $this->data;
    }

    private function getPrice()
    {

    }
}
