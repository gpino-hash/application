<?php


namespace App\Pagination\Filter;


use Illuminate\Database\Eloquent\Builder;

class UserEmailNotVerifiedQueryFilter
{

    /**
     * @param Builder $builder
     * @param $next
     * @return mixed
     */
    public function handle(Builder $builder, $next): mixed
    {
        return request()->has("verified")
            ? $next($builder)->whereNotNull("email_verified_at")
            : $next($builder)->whereNull("email_verified_at");
    }
}