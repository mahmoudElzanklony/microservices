<?php

namespace App\Http\Resources;

use App\Models\languages;
use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'user'=>UserResource::make($this->whenLoaded('user')),
            'sec_attr_data'=>SectionAttributeResource::collection($this->whenLoaded('sec_attr_data')),
            'privileges'=>PrivilegeResource::collection($this->whenLoaded('privileges')),
            'style'=>StyleResource::make($this->whenLoaded('style')),
            'type'=>$this->type,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
        ];

        if(request()->hasHeader('AllLangs')){
            $output  = $this->get_data_with_all_lang();
            return array_merge($init,$output['main_title'],$output['sub_title']);
        }else if(request()->hasHeader('mix')){
            $output  = $this->get_data_with_all_lang();
            $data = [
                'main_title'=>FormRequestHandleInputs::handle_output_column($this->main_title),
                'sub_title'=>FormRequestHandleInputs::handle_output_column($this->sub_title) ?? [],
            ];
            return array_merge($init,$output['main_title'],$output['sub_title'],$data);
        }else{
            $data = [
                'main_title'=>FormRequestHandleInputs::handle_output_column($this->main_title),
                'sub_title'=>FormRequestHandleInputs::handle_output_column($this->sub_title) ?? [],
            ];
            return array_merge($init,$data);
        }
    }

    public function get_data_with_all_lang()
    {
        $langs = languages::query()->select('prefix')->get();
        // data
        $main_title = FormRequestHandleInputs::handle_output_column_for_all_lang('main_title',$this->main_title,$langs);
        $sub_title = FormRequestHandleInputs::handle_output_column_for_all_lang('sub_title',$this->sub_title,$langs);
        return [
          'main_title'=>$main_title,
          'sub_title'=>$sub_title,
        ];
    }
}
