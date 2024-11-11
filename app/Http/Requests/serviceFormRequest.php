<?php

namespace App\Http\Requests;

use App\Services\FormRequestHandleInputs;
use Illuminate\Foundation\Http\FormRequest;

class serviceFormRequest extends FormRequest
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
            'type'=>'required|in:contact,in_mail',
        ];
        $arr = FormRequestHandleInputs::handle($arr,['main_title','sub_title:nullable']);
        return $arr;
    }
}
