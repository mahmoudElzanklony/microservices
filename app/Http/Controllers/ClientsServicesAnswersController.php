<?php

namespace App\Http\Controllers;

use App\Http\patterns\builder\ClientServiceAnswerBuilder;
use App\Http\Requests\clientSectionAnswersFormRequest;
use App\Http\Resources\ClientServicePrivateDataResource;
use App\Models\clients_services_sections_private_data;
use App\Services\Messages;
use Illuminate\Http\Request;

class ClientsServicesAnswersController extends Controller
{
    public function index()
    {
        $data = clients_services_sections_private_data::query()
                ->with('answers.attribute')
                ->when(request()->filled('service_id'),function ($e){
                    $e->where('service_id',request()->input('service_id'));
                })->orderBy('id','DESC')
                ->paginate(10);
        return ClientServicePrivateDataResource::collection($data);
    }
    //
    public function save_answers(clientSectionAnswersFormRequest $request)
    {
        $data = $request->validated();
        $data['ip']= request()->ip();
        $builder = new ClientServiceAnswerBuilder($data);
        try{
            $builder->create_private_data()->create_data();
            return Messages::success(__('messages.saved_successfully'));
        }catch (\Exception $exception){
            return Messages::error($exception->getMessage());
        }
    }
}
