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
            'amount'        => ['required', 'numeric', 'min:0.01'],
            'description'   => ['required', 'string', 'max:255'],
        ];
    }
}