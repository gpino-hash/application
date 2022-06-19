<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    #[ArrayShape(["email" => "mixed", "name" => "mixed", "status" => "mixed"])]
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            "id" => $this->uuid,
            "email" => $this->email,
            "name" => $this->name,
            "status" => $this->status,
            "user_information" => new UserInformationResource($this->userInformation),
        ];
    }
}
