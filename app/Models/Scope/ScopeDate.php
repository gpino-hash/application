<?php


namespace App\Models\Scope;


trait ScopeDate
{
    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeFrom($query, $value): mixed
    {
        return empty($value) ? $query : $query->whereDate('created_at', '>=', $value);
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeTo($query, $value): mixed
    {
        return empty($value) ? $query : $query->whereDate('created_at', '<=', $value);
    }
}