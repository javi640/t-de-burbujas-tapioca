<?php

namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class OpenShiftRequest extends FormRequest
{
    public function authorize(): bool { return true; }
 
    public function rules(): array
    {
        return [
            'initial_cash'   => ['required', 'numeric', 'min:0'],
            'notes'          => ['nullable', 'string', 'max:500'],
            // stock es un array: stock[product_id] = quantity
            'stock'          => ['required', 'array', 'min:1'],
            'stock.*'        => ['required', 'integer', 'min:0'],
        ];
    }
 
    public function messages(): array
    {
        return [
            'initial_cash.required' => 'El efectivo inicial es obligatorio.',
            'initial_cash.min'      => 'El efectivo inicial no puede ser negativo.',
            'stock.required'        => 'Debes ingresar el stock inicial.',
        ];
    }
}