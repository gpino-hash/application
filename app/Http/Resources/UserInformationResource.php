<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->uuid,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "age" => Carbon::make($this->birthdate)->age,
            "avatar" => new PictureResource($this->avatar),
            "phone" => new PhoneResource($this->phone),
            "address" => new AddressResource($this->address),
        ];
    }
}
