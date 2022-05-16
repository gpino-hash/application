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
}