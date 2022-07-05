<?php


namespace App\Models;

use Illuminate\Support\Str;

trait GlobalModelFunctions
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string)Str::uuid();
        });
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyName()
    {
        return 'uuid';
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeStatus($query, $value): mixed
    {
        return empty($value) ? $query : $query->where('status', $value);
    }
}