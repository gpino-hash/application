<?php


namespace App\Models\Scope;


trait GenericScope
{

    /**
     * @param $query
     * @param $key
     * @param $value
     * @return mixed
     */
    public function scopeGenericSearch($query, $key, $value): mixed
    {
        return empty($value) ? $query : $query->where($key, "LIKE", "%" . $value . "%");
    }

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