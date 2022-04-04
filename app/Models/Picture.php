<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picture extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "title",
        "description",
        "url",
        "thumbnail_url",
        "tags",
    ];

    /**
     * @return BelongsTo
     */
    public function userInformation(): BelongsTo
    {
        return $this->belongsTo(UserInformation::class);
    }
}
