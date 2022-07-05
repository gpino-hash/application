<?php


namespace App\Models\Builder;

use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends Builder
{
    /**
     * @return UserBuilder
     */
    public function isAdmin(): static
    {
        return $this->tokenCan("admin");
    }

    /**
     * @param string $email
     * @param string $status
     * @return UserBuilder
     */
    public function getUserByEmail(string $email, string $status): static
    {
        return $this->where("email", $email)
            ->where("status", $status);
    }

    /**
     * @return static
     */
    public function getPaginatorQuery(): static
    {
        return $this->with(["userInformation" => fn($query) => $query->with("avatar", "phone", "address")]);
    }
}