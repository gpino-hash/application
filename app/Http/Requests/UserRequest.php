<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rulesUser = [
            "name" => [
                "unique:users,name",
            ],
            "email" => [
                "unique:users,email",
                "email",
            ],
            "password" => [
                "nullable",
                "max:50",
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];

        if ($this->has("password_confirmation")) {
            Arr::prepend($rulesUser["password"], "confirmed");
        }

        if ($this->isMethod(self::METHOD_POST)) {
            Arr::prepend($rulesUser["name"], "required");
            Arr::prepend($rulesUser["email"], "required");
        }

        $rulesUserInformation = [
            "id_number" => "string",
            "firstname" => "string",
            "lastname" => "string",
            "birthdate" => "date",
        ];

        return match ($this->method()) {
            self::METHOD_POST => $rulesUser,
            self::METHOD_PUT, self::METHOD_PATCH => array_merge($rulesUser, $rulesUserInformation)
        };
    }
}
