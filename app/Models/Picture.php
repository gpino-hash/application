<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picture extends Model
{
    use HasFactory, SoftDeletes, GlobalModelFunctions;

    public $incrementing = false;

    public $primaryKey = "uuid";

    protected $fillable = [
        "title",
        "description",
        "url",
        "thumbnail_url",
        "tags",
        "pictureable_id",
        "status",
    ];

    /**
     * @return MorphTo
     */
    public function pictureable(): MorphTo
    {
        return $this->morphTo();
    }
}
