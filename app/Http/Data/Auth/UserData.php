<?php

namespace App\Http\Data\Auth;

use App\Http\UseCase\Status;

class UserData
{
    private $username;
    private $email;
    private $password;
    private $status;
    private $tags;

    /**
     * @param $username
     * @param $email
     * @param $password
     * @param Status $status
     * @param $tags
     */
    public function __construct($username, $email, $password, Status $status, $tags)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->status = $status;
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
