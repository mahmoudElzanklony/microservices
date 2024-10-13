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
            'latitude'=>'nullable',
            'longitude'=>'nullable',
            'url'=>'filled',
            'info'=>'filled',
            'ids'=>'filled|array',
            'ids.*'=>'filled',
            'attribute_id'=>'required|array',
            'attribute_id.*'=>'required|exists:attributes,id',
            'answer' => 'required|array',
            'answer.*' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    // Check if the value is a file
                    if (request()->hasFile($attribute)) {
                        $file = request()->file($attribute);
                        // Apply file validation rules
                        if (!$file->isValid()) {
                            $fail($attribute . ' is not a valid file.');
                        }
                        if ($file->getSize() > 30720 * 1024) { // 30MB in kilobytes
                            $fail($attribute . ' exceeds the maximum allowed size of 30MB.');
                        }
                        $allowedMimes = ['png', 'jpg', 'jpeg', 'pdf', 'docx', 'mp4'];
                        if (!in_array($file->getClientOriginalExtension(), $allowedMimes)) {
                            $fail($attribute . ' must be a file of type: png, jpg, jpeg, pdf, docx, mp4.');
                        }
                    }
                }
            ],
            'files'=>'nullable|array',
            'files.*'=>'nullable',
        ];
    }
}
