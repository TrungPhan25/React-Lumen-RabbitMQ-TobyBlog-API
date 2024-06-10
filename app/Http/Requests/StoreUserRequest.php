<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Validation\Rules\Password;


class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name'           => 'required|string|max:55',
            'email'          => 'required|email|unique:users,email',
            'link_facebook'  => 'string',
            'link_instagram' => 'string',
            'link_x'         => 'string',
            'password'       => [
                'required',
                Password::min(8)
                    ->letters()
                    ->symbols(),
            ]
        ];
    }
}
