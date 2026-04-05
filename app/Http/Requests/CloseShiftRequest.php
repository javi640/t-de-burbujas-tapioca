<?php

namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class CloseShiftRequest extends FormRequest
{
    public function authorize(): bool { return true; }
 
    public function rules(): array
    {
        return [
            'reported_cash' => ['required', 'numeric', 'min:0'],
            'notes'         => ['nullable', 'string', 'max:1000'],
        ];
    }
 
    public function messages(): array
    {
        return [
            'reported_cash.required' => 'Debes declarar el efectivo en caja.',
            'reported_cash.min'      => 'El efectivo declarado no puede ser negativo.',
        ];
    }
}