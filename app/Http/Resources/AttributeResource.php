<?php

namespace App\Http\Resources;

use App\Models\languages;
use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $init = [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'name'=>$this->name,
            'visibility'=>$this->visibility,
            'type'=>$this->type,
            'icon'=>$this->icon,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
        ];
        if(request()->hasHeader('AllLangs')){
            $langs = languages::query()->select('prefix')->get();
            // data
            $label = FormRequestHandleInputs::handle_output_column_for_all_lang('name',$this->label,$langs);
            $placeholder = FormRequestHandleInputs::handle_output_column_for_all_lang('name',$this->label,$langs);
            return array_merge($init,$label,$placeholder);
        }else{
            $data = [
                'label'=>FormRequestHandleInputs::handle_output_column($this->label),
                'placeholder'=>FormRequestHandleInputs::handle_output_column($this->placeholder),
            ];
            return array_merge($init,$data);
        }
    }
}
