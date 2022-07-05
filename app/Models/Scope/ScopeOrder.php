<?php


namespace App\Models\Scope;


trait ScopeOrder
{

    /**
     * @param $query
     * @param string $value
     * @return mixed
     */
    public function scopeSortCreatedAt($query, $value = "ASC"): mixed
    {
        return $query->reorder()->orderBy("created_at", $value);
    }

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
}