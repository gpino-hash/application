<?php

namespace App\Models;

use App\Enums\UserType;
use App\Models\Builder\UserBuilder;
use App\Models\Scope\GenericScope;
use App\Models\Scope\ScopeOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JetBrains\PhpStorm\Pure;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes,
        GenericScope,
        ScopeOrder,
        GlobalModelFunctions;

    public $incrementing = false;

    public $primaryKey = "uuid";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'tags',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'type' => UserType::class,
    ];

    /**
     * @return HasOne
     */
    public function userInformation(): HasOne
    {
        return $this->hasOne(UserInformation::class);
    }

    public function verify(): void
    {
        self::forceFill([
            'email_verified_at' => now(),
        ])->save();
    }

    /**
     * @return UserBuilder|Builder
     */
    public static function query(): UserBuilder|Builder
    {
        return parent::query();
    }

    /**
     * @param $query
     * @return UserBuilder
     */
    #[Pure]
    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    /** ---------- SORT ------------- */

    /**
     * @param $query
     * @param $key
     * @param $value
     * @return mixed
     */
    public function scopeGenericSortBy($query, $key, $value): mixed
    {
        return empty($value) ? $query : $query->reorder()->orderBy($key, $value);
    }

    /** ---------- FILTER ------------- */

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeFromEmailVerified($query, $value): mixed
    {
        return empty($value) ? $query : $query->whereDate('created_at', '>=', $value);
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeToEmailVerified($query, $value): mixed
    {
        return empty($value) ? $query : $query->whereDate('created_at', '<=', $value);
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeSearch($query, $value): mixed
    {
        return empty($value)
            ? $query
            : $query->where('name', "LIKE", "%" . $value . "%")
                ->orWhere("email", "LIKE", "%" . $value . "%")
                ->orWhereRelation("userInformation", "firstname", "LIKE", "%" . $value . "%")
                ->orWhereRelation("userInformation", "lastname", "LIKE", "%" . $value . "%");
    }
}
