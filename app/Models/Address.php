<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "country",
        "state",
        "city",
        "address",
        "type",
        "tags",
        "status",
        "user_information_id",
    ];

    /**
     * @return BelongsTo
     */
    public function userInformation(): BelongsTo
    {
        return $this->belongsTo(UserInformation::class);
    }
}
