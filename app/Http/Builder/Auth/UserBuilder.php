<?php

namespace App\Http\Builder\Auth;

use App\Http\Builder\Builder;
use App\Http\Data\Auth\UserData;
use App\UseCase\Status;
use JetBrains\PhpStorm\Pure;

class UserBuilder extends Builder
{
    private $username;
    private $email;
    private $password;
    private $status;
    private $tags;
    private $name;

    #[Pure]
    public static function builder(): UserBuilder
    {
        return new UserBuilder();
    }

    /**
     * @param string $username
     * @return $this
     */
    public function username(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function email(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function password(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param Status $status
     * @return $this
     */
    public function status(Status $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param array $tags
     * @return $this
     */
    public function tags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    #[Pure]
    public function build(): UserData
    {
        return new UserData($this->username, $this->email, $this->password, $this->status, $this->tags, $this->name);
    }
}
