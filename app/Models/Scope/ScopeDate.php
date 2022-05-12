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
        if (empty($value)) {
            return $query;
        }

        return $query->whereDate('created_at', '>=', $value);
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeTo($query, $value): mixed
    {
        if (empty($value)) {
            return $query;
        }

        return $query->whereDate('created_at', '<=', $value);
    }
}