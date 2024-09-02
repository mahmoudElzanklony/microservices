<?php

namespace App\Http\Resources;

use App\Models\languages;
use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
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
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
        ];
        if(request()->hasHeader('AllLangs')){
            $langs = languages::query()->select('prefix')->get();
            // data
            $name = FormRequestHandleInputs::handle_output_column_for_all_lang('name',$this->label,$langs);
            return array_merge($init,$name);
        }else if(request()->hasHeader('mix')){
            $output  = $this->get_data_with_all_lang();
            $data = [
                'name'=>FormRequestHandleInputs::handle_output_column($this->name),
            ];
            return array_merge($init,$output['name'],$data);
        }else{
            $data = [
                'name'=>FormRequestHandleInputs::handle_output_column($this->name),
            ];
            return array_merge($init,$data);
        }
    }
    public function get_data_with_all_lang()
    {
        $langs = languages::query()->select('prefix')->get();
        // data
        $name = FormRequestHandleInputs::handle_output_column_for_all_lang('name',$this->name,$langs);
        return [
            'name'=>$name,
        ];
    }
}
