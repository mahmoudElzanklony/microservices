<?php

namespace App\Http\Requests;

use App\Services\FormRequestHandleInputs;
use Illuminate\Foundation\Http\FormRequest;

class serviceSecAttrFormRequest extends FormRequest
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
            'service_id'=>'required|exists:services,id',
            'item_id'=>'filled|array',
            'item_id.*'=>'filled|exists:services_sections_datas,id',
            'attribute_id'=>'required|array',
            'attribute_id.*'=>'required|exists:attributes,id',
            'types'=>'required|array',
            'types.*'=>'required',
            'type'=>'required',
            'style'=>'filled',
        ];
        $arr = FormRequestHandleInputs::handle($arr,['main_title','sub_title:nullable']);
        return $arr;

    }
}
