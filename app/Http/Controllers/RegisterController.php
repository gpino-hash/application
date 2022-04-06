<?php

namespace App\Http\Controllers;

use App\Factory\Auth\IApi;
use App\Http\Builder\Auth\UserBuilder;
use App\Http\Data\Auth\UserData;
use App\Http\Requests\RegisterRequest;
use App\Http\Traits\ResponseWithHttpStatus;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use ResponseWithHttpStatus;

    private IApi $api;

    /**
     * @param IApi $api
     */
    public function __construct(IApi $api)
    {
        $this->api = $api;
        $this->middleware('guest');
    }


    public function register(RegisterRequest $request)
    {

    }

    /**
     * @param Request $request
     * @return UserData
     */
    private function getUser(Request $request): UserData
    {
        return UserBuilder::builder()
            ->username($request->input("username"))
            ->email($request->input("email"))
            ->password($request->input("password"))
            ->build();
    }
}
