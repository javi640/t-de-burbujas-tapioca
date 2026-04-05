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
            'username'  => ['required', 'string', 'max:50', Rule::unique('users','username')->ignore($userId)],
            'email'     => ['required', 'email', Rule::unique('users','email')->ignore($userId)],
            'password'  => ['nullable', 'string', 'min:8'],
            'role_id'   => ['required', 'exists:roles,id'],
            'branch_id' => ['required', 'exists:branches,id'],
        ];
    }
}