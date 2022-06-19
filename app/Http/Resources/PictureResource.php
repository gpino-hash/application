<?php

namespace App\Http\Resources;

use JetBrains\PhpStorm\ArrayShape;

class PictureResource extends AbstractResource
{

    /**
     * @inheritDoc
     */
    protected function excludedAttribute(): array
    {
        return [
            "pictureable_id",
            "pictureable_type",
            "created_at",
            "updated_at",
            "deleted_at",
        ];
    }

    /**
     * @return string[]
     */
    #[ArrayShape(["url" => "string", "uuid" => "string"])]
    protected function keyChange(): array
    {
        return [
            "url" => "image",
            "uuid" => "id",
        ];
    }
}
