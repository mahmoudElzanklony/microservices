<?php

namespace App\Http\patterns\builder;

use App\Models\attributes;
use App\Models\clients_services_sections_data;
use App\Models\clients_services_sections_private_data;
use App\Http\Traits\upload_image;
class ClientServiceAnswerBuilder
{
    use upload_image;
    private $privateDataObj = null;
    public function __construct(private $data){}

    public function create_private_data()
    {
        $this->privateDataObj = clients_services_sections_private_data::query()
            ->updateOrCreate([
                'id'=>$this->data['id'] ?? null
            ],[
                'service_id'=>$this->data["service_id"],
                'ip'=>$this->data["ip"],
                'latitude'=>$this->data["latitude"],
                'longitude'=>$this->data["longitude"],
                'info'=>$this->data["info"] ?? null,
            ]);
        return $this;
    }

    public function create_data()
    {
        foreach ($this->data['attribute_id'] as $key => $value) {
            // Check if the answer is a file
            $file_num = 0;
            if(array_key_exists($key,$this->data['answer'])){
                $answer = $this->data['answer'][$key];
            }/*else{
                $answer = $this->data['files'][$file_num] ?? '';
            }*/
            /*if($answer instanceof \Illuminate\Http\UploadedFile){
                var_dump($this->data['attribute_id']);
                var_dump($this->data['answer']);
            }*/
            $type = 'text';
            if ($answer instanceof \Illuminate\Http\UploadedFile) {
                // Handle the file upload
                try {
                    $answer = $this->uploadGeneralFile($answer);
                } catch (\Exception $e) {
                    $answer = null;
                }
                $file_num++;
                $type = 'file';
            }
            clients_services_sections_data::query()->create([
                'attribute_id'=>$this->data['attribute_id'][$key],
                'service_section_data_id'=>$this->privateDataObj->id,
                'answer'=>$answer,
                'answer_type'=>$type,
            ]);
        }
    }
}
