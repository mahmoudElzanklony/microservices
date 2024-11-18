<?php

namespace App\Http\Controllers;

use App\Filters\EndDateFilter;
use App\Filters\NameFilter;
use App\Filters\sections\SectionIdFilter;
use App\Filters\services\MainTitleFilter;
use App\Filters\services\SubTitleFilter;
use App\Filters\StartDateFilter;
use App\Filters\UserIdFilter;
use App\Http\Requests\serviceFormRequest;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\ServiceResource;
use App\Models\attributes;
use App\Models\sections;
use App\Models\services;
use App\Services\FormRequestHandleInputs;
use App\Services\Messages;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class ServiceControllerResource extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->except('show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return auth()->user()->roleName() == 'member';
        $data = services::query()
            ->when(auth()->user()->roleName() == 'owner',fn($e) => $e->where('user_id',auth()->id()))
            ->when(auth()->user()->roleName() == 'member',
                fn($e) => $e->whereHas('privileges',
                    fn($q) => $q->where('user_id',auth()->id())
                              ->whereHas('controls.privilege',fn($x) => $x->where('name','=','view'))
                ))
            ->when(auth()->user()->roleName() == 'client',fn($e) => $e->whereHas('private_answers.owner'),fn($q) => $q->where('user_id','=',auth()->id()))
            ->when(auth()->user()->roleName() == 'admin',fn($e) => $e->with('user'))
            ->orderBy('id','DESC');
        $output  = app(Pipeline::class)
            ->send($data)
            ->through([
                UserIdFilter::class,
                NameFilter::class,
                MainTitleFilter::class,
                SubTitleFilter::class,
                StartDateFilter::class,
                EndDateFilter::class
            ])
            ->thenReturn()
            ->paginate(request('limit') ?? 10);
        return ServiceResource::collection($output);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function save($data)
    {
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['main_title','sub_title']);
        if(!(array_key_exists('user_id',$data))){
            $data['user_id'] = auth()->id();
        }
        $output = services::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        return Messages::success(__(trans('messages.saved_successfully')),ServiceResource::make($output));

    }
    public function store(serviceFormRequest $request)
    {
        //
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        return $this->save($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = services::query()
            ->where('id','=',$id)->FailIfNotFound('not found');
        if(request()->filled('check_owner') && request()->filled('user_id')){
            if($data->user_id != request('user_id')){
                return Messages::error('cant access this services','401');
            }
        }
        return ServiceResource::make($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(serviceFormRequest $request, string $id)
    {
        //
        $data = $request->validated();
        $data['user_id'] = services::query()
            ->where('id',$id)->FailIfNotFound(__('errors.not_found'))->user_id;
        if(!($data['user_id'] == auth()->id() || auth()->user()->role->name == 'admin')){
            return abort(403);
        }
        $data['id'] = $id;
        return $this->save($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
