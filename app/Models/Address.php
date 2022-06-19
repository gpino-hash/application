<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes, GlobalModelFunctions;

    public $incrementing = false;

    public $primaryKey = "uuid";

    protected $fillable = [
        "country",
        "state",
        "city",
        "postal_code",
        "address",
        "type",
        "tags",
        "status",
        "addressable_id",
        "country_uuid",
    ];

    /**
     * @return BelongsTo
     */
    public function addressable(): BelongsTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function country(): HasMany
    {
        return $this->hasMany(Country::class);
    }
}
