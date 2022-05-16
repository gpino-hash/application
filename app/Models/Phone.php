<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phone extends Model
{
    use HasFactory, SoftDeletes, GlobalModelFunctions;

    public $incrementing = false;

    public $primaryKey = "uuid";

    protected $fillable = [
        "phone",
        "type",
        "operator",
        "tags",
        "status",
        "phoneable_id",
    ];

    /**
     * @return MorphTo
     */
    public function phoneable(): MorphTo
    {
        return $this->morphTo();
    }
}
