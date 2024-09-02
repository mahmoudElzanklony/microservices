<?php

namespace App\Http\Requests;

use App\Services\FormRequestHandleInputs;
use Illuminate\Foundation\Http\FormRequest;

class sectionFormRequest extends FormRequest
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
        $arr = [
            'id'=>'filled',
            'type'=>'filled',
            'user_id'=>'filled|exists:users,id',
            'visibility'=>'required',
            'attributes'=>'filled|array',
            'attributes.*'=>'filled',
        ];
        $arr = FormRequestHandleInputs::handle($arr,['name']);
        return $arr;

    }
}
