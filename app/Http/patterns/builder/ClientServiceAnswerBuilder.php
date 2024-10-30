<?php

namespace App\Http\patterns\builder;

use App\Actions\SaveMemberAction;
use App\Http\Enum\ServiceTypeEnum;
use App\Models\attributes;
use App\Models\clients_services_owners;
use App\Models\clients_services_sections_data;
use App\Models\clients_services_sections_private_data;
use App\Http\Traits\upload_image;
use App\Models\services;
use App\Models\User;
use App\Services\Messages;
use Illuminate\Support\Str;

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
                'latitude'=>$this->data["latitude"] ?? null,
                'longitude'=>$this->data["longitude"] ?? null,
                'info'=>$this->data["info"] ?? null,
            ]);
        return $this;
    }

    public function create_data()
    {
        $email_check = $this->if_email_found_with_service_in_mail();
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
            if($email_check == $this->data['attribute_id'][$key]){
                // create user with email
                $user = $this->create_user($answer);
                // create answer owner service
                $this->create_owner($this->privateDataObj->id,$user->id);
            }
            clients_services_sections_data::query()->create([
                'attribute_id'=>$this->data['attribute_id'][$key],
                'service_section_data_id'=>$this->privateDataObj->id,
                'answer'=>$answer,
                'answer_type'=>$type,
            ]);
        }
    }

    public function if_email_found_with_service_in_mail()
    {
        $service = services::query()->find($this->data["service_id"]);
        if($service->type == ServiceTypeEnum::in_mail->value){
            $email_attr = attributes::query()->where('name','=','email')->first();
            return $email_attr?->id;
        }
        return false;
    }

    public function create_user($email)
    {
        $check_mail = User::query()->where('email','=',$email)->first();
        if($check_mail != null){
            abort(400,__('errors.email_exists_choose_another'),[
                'accept'=>'application/json'
            ]);
        }
        return SaveMemberAction::save([
            'username'=>Str::before($email,'@'),
            'email'=>$email,
            'password'=>time(),
        ],'client');

    }

    public function create_owner($private_id,$user_id)
    {
        clients_services_owners::query()->create([
           'service_private_answer_id'=>$private_id,
           'user_id'=>$user_id
        ]);
    }
}
