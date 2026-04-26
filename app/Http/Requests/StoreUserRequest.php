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
            'name.required'            => 'El nombre es obligatorio.',
            'name.max'                 => 'El nombre no puede superar 100 caracteres.',
            'username.required'        => 'El nombre de usuario es obligatorio.',
            'username.unique'          => 'Ese nombre de usuario ya está en uso.',
            'username.regex'           => 'El usuario solo puede contener letras, números y guión bajo.',
            'email.required'           => 'El correo electrónico es obligatorio.',
            'email.email'              => 'El correo no tiene un formato válido.',
            'email.unique'             => 'Ese correo ya está registrado.',
            'email.regex'              => 'Solo se permiten correos de Gmail (@gmail.com).',
            'password.required'        => 'La contraseña es obligatoria.',
            'password.min'             => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'       => 'Las contraseñas no coinciden.',
            'role_id.required'         => 'Debes seleccionar un rol.',
            'role_id.exists'           => 'El rol seleccionado no existe.',
            'branch_id.required'       => 'Debes seleccionar una sucursal.',
            'branch_id.exists'         => 'La sucursal seleccionada no existe.',
        ];
    }
}