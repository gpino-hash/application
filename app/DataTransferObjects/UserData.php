<?php


namespace App\DataTransferObjects;

use App\Models\User;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class UserData extends AbstractData
{

    /**
     * UserData constructor.
     * @param string|null $name
     * @param string|null $email
     * @param string|null $password
     * @param string|null $status
     */
    #[Pure]
    public function __construct(public string|null $name = null,
                                public string|null $email = null,
                                public string|null $password = null,
                                public string|null $status = null)
    {
        parent::__construct(User::class);
    }

    /**
     * @inheritDoc
     */
    protected function getColumnsFilter(): array
    {
        return [
            "name",
            "email",
            "from",
            "to",
        ];
    }

    /**
     * @inheritDoc
     */
    public static function fromRequest(Request $request): self
    {
        return self::from($request->only("name", "email", "password", "status"));
    }
}