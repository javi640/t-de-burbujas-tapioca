<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCashMovementRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'movement_type' => ['required', 'in:INCOME,EXPENSE'],
            'amount'        => ['required', 'numeric', 'min:0.01', 'max:100000'],
            'description'   => ['required', 'string', 'min:3', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'movement_type.required' => 'El tipo de movimiento es obligatorio.',
            'movement_type.in'       => 'Tipo de movimiento inválido.',
            'amount.required'        => 'El monto es obligatorio.',
            'amount.min'             => 'El monto debe ser mayor a 0.',
            'amount.max'             => 'El monto no puede superar Bs 100,000.',
            'description.required'   => 'La descripción es obligatoria.',
            'description.min'        => 'La descripción debe tener al menos 3 caracteres.',
            'description.max'        => 'La descripción no puede superar los 255 caracteres.',
        ];
    }
}