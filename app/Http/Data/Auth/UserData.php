<?php

namespace App\Http\Data\Auth;

use App\UseCase\Status;

class UserData
{
    private $email;
    private $password;
    private $status;
    private $tags;
    private $name;

    /**
     * @param $email
     * @param $password
     * @param $status
     * @param $tags
     * @param $name
     */
    public function __construct($email, $password, $status, $tags, $name)
    {
        $this->email = $email;
        $this->password = $password;
        $this->status = $status;
        $this->tags = $tags;
        $this->name = $name;
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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
