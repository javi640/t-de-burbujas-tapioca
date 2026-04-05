<?php

namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool { return true; }
 
    public function rules(): array
    {
        return [
            'payment_method'         => ['required', 'in:CASH,QR'],
            'items'                  => ['required', 'array', 'min:1'],
            'items.*.product_id'     => ['required', 'exists:products,id'],
            'items.*.quantity'       => ['required', 'integer', 'min:1'],
        ];
    }
 
    public function messages(): array
    {
        return [
            'payment_method.required' => 'Selecciona el método de pago.',
            'payment_method.in'       => 'Método de pago inválido.',
            'items.required'          => 'Debes agregar al menos un producto.',
            'items.*.product_id.exists'  => 'Producto no encontrado.',
            'items.*.quantity.min'       => 'La cantidad mínima es 1.',
        ];
    }
}