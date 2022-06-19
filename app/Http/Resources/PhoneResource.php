<?php

namespace App\Http\Resources;

use JetBrains\PhpStorm\ArrayShape;

class PhoneResource extends AbstractResource
{

    /**
     * @return string[]
     */
    protected function excludedAttribute(): array
    {
        return [
            "phoneable_type",
            "phoneable_id",
            "created_at",
            "updated_at",
            "deleted_at",
        ];
    }

    /**
     * @return string[]
     */
    #[ArrayShape(["uuid" => "string"])]
    protected function keyChange(): array
    {
        return [
            "uuid" => "id",
        ];
    }
}
