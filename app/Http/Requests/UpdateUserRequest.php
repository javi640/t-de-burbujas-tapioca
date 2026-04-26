<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $userId = $this->route('user')->id;
        return [
            'name'      => ['required', 'string', 'max:100'],
            'username'  => ['required', 'string', 'max:50', Rule::unique('users','username')->ignore($userId), 'regex:/^[a-zA-Z0-9_]+$/'],
            'email'     => ['required', 'email', Rule::unique('users','email')->ignore($userId), 'regex:/@gmail\.com$/i'],
            'password'  => ['nullable', 'string', 'min:8'],
            'role_id'   => ['required', 'exists:roles,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex' => 'El usuario solo puede contener letras, números y guión bajo (_).',
            'email.regex'    => 'El correo debe ser una cuenta de Gmail (@gmail.com).',
        ];
    }
}