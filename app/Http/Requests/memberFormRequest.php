<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class memberFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id'=>'filled',
            'email' => 'required|unique:users,email,' .request('id') ?? null,
            'username' => 'required',
            'phone' => 'required|unique:users,phone,'. request('id') ?? null,
            'password' => 'nullable',
            'item'=>'required|array',
            'item.*'=>'nullable'
        ];
    }
}
