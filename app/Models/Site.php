<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Site extends Model
{
    use HasFactory, GlobalModelFunctions;

    protected $fillable = [
        "name",
        "branch_office",
        "symbol",
        "status",
        "currency_uuid",
    ];

    /**
     * @return MorphMany
     */
    public function address(): MorphMany
    {
        return $this->morphMany(Address::class, "addressable");
    }
}
