<?php


namespace App\Models\Scope;


trait ScopeOrder
{

    /**
     * @param $query
     * @param string $value
     * @return mixed
     */
    public function scopeSortId($query, $value = "ASC"): mixed
    {
        return $query->reorder()->orderBy("id", $value);
    }
}