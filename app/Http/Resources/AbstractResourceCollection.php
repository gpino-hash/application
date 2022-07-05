<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

abstract class AbstractResourceCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        $data["data"] = $this->getData();
        $data["meta"] = $this->getPagination();
        $data["links"] = $this->getLinks();
        return $data;
    }

    abstract protected function getData(): array;

    /**
     * @return array
     */
    #[ArrayShape(['total' => "mixed", 'count' => "int", 'per_page' => "mixed", 'current_page' => "mixed", 'total_pages' => "mixed"])]
    private function getPagination(): array
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }

    private function getLinks()
    {
        return [
            "path" => $this->resolveCurrentPath(),
            "next_page" => $this->nextPageUrl(),
            "last_page" => $this->previousPageUrl(),
        ];
    }
}