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
}