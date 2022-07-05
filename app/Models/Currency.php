<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory, SoftDeletes, GlobalModelFunctions;

    public $incrementing = false;

    public $primaryKey = "uuid";

    protected $fillable = [
        "name",
        "description",
        "symbol",
        "decimal_places",
        "decimal_separator",
        "thousands_separator",
    ];

    /**
     * @return BelongsTo
     */
    public function countries(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
