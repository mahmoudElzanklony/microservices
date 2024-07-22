<?php

namespace App\Http\Requests;

use App\Services\FormRequestHandleInputs;
use Illuminate\Foundation\Http\FormRequest;

class attributeFormRequest extends FormRequest
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
            'name'=>'required',
            'user_id'=>'filled|exists:users,id',
            'visibility'=>'required',
            'type'=>'required',
            'icon'=>'required',
        ];
        $arr = FormRequestHandleInputs::handle($arr,['label','placeholder']);
        return $arr;
    }
}