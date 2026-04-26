<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:100'],
            'username'  => ['required', 'string', 'max:50', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email'     => ['required', 'email', 'unique:users,email', 'regex:/@gmail\.com$/i'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'role_id'   => ['required', 'exists:roles,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex'           => 'El usuario solo puede contener letras, números y guión bajo (_).',
            'email.regex'              => 'El correo debe ser una cuenta de Gmail (@gmail.com).',
            'password.confirmed'       => 'Las contraseñas no coinciden.',
            'password.min'             => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}