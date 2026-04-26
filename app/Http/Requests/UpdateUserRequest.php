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
            'username'  => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9_]+$/', Rule::unique('users', 'username')->ignore($userId)],
            'email'     => ['required', 'email', 'regex:/@gmail\.com$/i', Rule::unique('users', 'email')->ignore($userId)],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id'   => ['required', 'exists:roles,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'El nombre es obligatorio.',
            'name.max'            => 'El nombre no puede superar 100 caracteres.',
            'username.required'   => 'El nombre de usuario es obligatorio.',
            'username.unique'     => 'Ese nombre de usuario ya está en uso.',
            'username.regex'      => 'El usuario solo puede contener letras, números y guión bajo.',
            'email.required'      => 'El correo electrónico es obligatorio.',
            'email.email'         => 'El correo no tiene un formato válido.',
            'email.unique'        => 'Ese correo ya está registrado.',
            'email.regex'         => 'Solo se permiten correos de Gmail (@gmail.com).',
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'  => 'Las contraseñas no coinciden.',
            'role_id.required'    => 'Debes seleccionar un rol.',
            'branch_id.required'  => 'Debes seleccionar una sucursal.',
        ];
    }
}