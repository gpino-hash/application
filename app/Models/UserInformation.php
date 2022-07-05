<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInformation extends Model
{
    use HasFactory, SoftDeletes, GlobalModelFunctions;

    public $incrementing = false;

    public $primaryKey = "uuid";

    protected $fillable = [
        "id_number",
        "firstname",
        "lastname",
        "birthdate",
        "nationality",
        "user_uuid",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id_number',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserInformation::class);
    }

    /**
     * @return MorphMany
     */
    public function phone(): MorphMany
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    /**
     * @return MorphOne
     */
    public function avatar(): MorphOne
    {
        return $this->morphOne(Picture::class, 'pictureable');
    }

    /**
     * @return MorphMany
     */
    public function address(): MorphMany
    {
        return $this->morphMany(Address::class, "addressable");
    }
}
