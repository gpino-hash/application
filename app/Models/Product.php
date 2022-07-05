<?php

namespace App\Models;

use App\Models\Builder\ProductBuilder;
use App\Models\Scope\GenericScope;
use App\Models\Scope\ScopeOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use JetBrains\PhpStorm\Pure;

class Product extends Model
{
    use HasFactory, GlobalModelFunctions, SoftDeletes, GenericScope, ScopeOrder;

    public $incrementing = false;

    public $primaryKey = "uuid";

    protected $fillable = [
        "title",
        "description",
        "stock",
        "price",
        "status",
        "site_uuid"
    ];

    /**
     * @return MorphMany
     */
    public function picture(): MorphMany
    {
        return $this->morphMany(Picture::class, 'pictureable');
    }

    /**
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * @return ProductBuilder|Builder
     */
    public static function query(): ProductBuilder|Builder
    {
        return parent::query();
    }

    /**
     * @param $query
     * @return ProductBuilder
     */
    #[Pure]
    public function newEloquentBuilder($query): ProductBuilder
    {
        return new ProductBuilder($query);
    }

    /** scopes */

    public function scopeSearch($query, $value)
    {
        return empty($value)
            ? $query
            : $query->where("title", "LIKE", "%" . $value . "%");
    }
}
