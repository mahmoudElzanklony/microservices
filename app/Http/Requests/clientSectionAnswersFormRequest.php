<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class clientSectionAnswersFormRequest extends FormRequest
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
            'service_id'=>'required|exists:services,id',
            'latitude'=>'required',
            'longitude'=>'required',
            'url'=>'filled',
            'info'=>'filled',
            'ids'=>'filled|array',
            'ids.*'=>'filled',
            'attribute_id'=>'required|array',
            'attribute_id.*'=>'required|exists:attributes,id',
            'answer'=>'required|array',
            'answer.*'=>'nullable',
            'files'=>'nullable|array',
            'files.*'=>'nullable',
        ];
    }
}
