<?php

namespace App\Models;

use App\Models\Scope\ScopeDate;
use App\Models\Scope\ScopeOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, ScopeDate, ScopeOrder;

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
    ];

    /**
     * @return HasOne
     */
    public function userInformation(): HasOne
    {
        return $this->hasOne(UserInformation::class);
    }

    /**
     * @param string $email
     * @param string $status
     * @return Model|Builder|null
     */
    public static function getUserByEmail(string $email, string $status): Model|Builder|null
    {
        return self::query()->where("email", $email)
            ->where("status", $status)
            ->first();
    }

    /**
     * @param array $data
     * @return Model|Builder
     */
    public static function create(array $data): Model|Builder
    {
        return self::query()->create($data);
    }

    /** SORT */

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeSortName($query, $value): mixed
    {
        return empty($value) ? $query : $query->reorder()->orderBy("name", $value);
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeSortEmail($query, $value): mixed
    {
        return empty($value) ? $query : $query->reorder()->orderBy("email", $value);
    }
}
