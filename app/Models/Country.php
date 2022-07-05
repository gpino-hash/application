<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes, GlobalModelFunctions;

    public $incrementing = false;

    public $primaryKey = "uuid";

    protected $fillable = [
        "name",
        "code",
        "locale",
    ];

    /**
     * @return HasMany
     */
    public function currencies(): HasMany
    {
        return $this->hasMany(Currency::class);
    }
}
