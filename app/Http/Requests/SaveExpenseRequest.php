<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where('user_id', auth()->id()),
            ],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'decimal:0,2', 'min:0.01', 'max:99999999.99'],
            'date' => ['required', 'date'],
        ];
    }
}
