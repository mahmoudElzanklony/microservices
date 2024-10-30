<?php

namespace App\Http\Controllers;

use App\Events\MyWebSocketEvent;
use App\Filters\clients\ServiceIdFilter;
use App\Filters\EndDateFilter;
use App\Filters\NameFilter;
use App\Filters\sections\NoOwnerShipFilter;
use App\Filters\sections\SectionIdFilter;
use App\Filters\StartDateFilter;
use App\Filters\TypeFilter;
use App\Filters\VisibilityFilter;
use App\Http\patterns\builder\ClientServiceAnswerBuilder;
use App\Http\Requests\clientSectionAnswersFormRequest;
use App\Http\Resources\ClientServicePrivateDataResource;
use App\Http\Resources\ServiceSecAttrResource;
use App\Models\clients_services_sections_private_data;
use App\Models\services;
use App\Models\services_privileges;
use App\Models\services_privileges_controls;
use App\Models\services_sections_data;
use App\Patterns\factory\answers\AuthorizeUserServiceFactory;
use App\Services\Messages;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class ClientsServicesAnswersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('save_answers');
    }
    public function index():ResourceCollection
    {
        $data = clients_services_sections_private_data::query()
                ->with('service')
                ->with('answers.attribute')
                ->when(auth()->user()->roleName() == 'member',function ($q){
                    $q->with('service.privileges')
                        ->whereHas('service.privileges',fn($q) => $q->where('user_id',auth()->id()));
                })
                ->when(auth()->user()->roleName() == 'owner',function ($q){
                    $q->whereHas('service',fn($q) => $q->where('user_id','=',auth()->id()));
                })
                ->when(auth()->user()->roleName() == 'client',function ($q){
                    $q->whereHas('client_service_owner',fn($q) => $q->where('user_id','=',auth()->id()));
                })
                ->when(request()->filled('service_id'),function ($e){
                    $e->where('service_id',request()->input('service_id'));
                })
            ->orderBy('id','DESC');

        $output  = app(Pipeline::class)
            ->send($data)
            ->through([
                ServiceIdFilter::class,
                StartDateFilter::class,
                EndDateFilter::class,
            ])
            ->thenReturn()
            ->paginate(request('limit') ?? 10);
        return ClientServicePrivateDataResource::collection($output);
    }

    public function authorize_user()
    {
        $status = AuthorizeUserServiceFactory::authorize(request('service_id')  , request('type') ?? '');
        return response()->json(['status'=>$status]);
    }

    public function columns(services $service_id)
    {
        $columns = services_sections_data::query()->with('attribute')
            ->where('service_id',request('service_id'))->get();
        return ServiceSecAttrResource::collection($columns);
    }
    //
    public function save_answers(clientSectionAnswersFormRequest $request)
    {
        DB::beginTransaction();
        $data = $request->validated();
        $data['ip']= request('url') ?? request()->ip();
        $builder = new ClientServiceAnswerBuilder($data);

        try{
            $builder->create_private_data()->create_data();
            DB::commit();
            return Messages::success(__('messages.saved_successfully'));
        }catch (\Exception $exception){
            return Messages::error($exception->getMessage());
        }
    }

    public function privileges()
    {
        $result = services_privileges::query()
            ->when(auth()->user()->roleName() == 'member',fn($e) => $e->where('user_id','=',auth()->id()))
            ->with('privileges')
        ->get();
        return $result;
    }
}
