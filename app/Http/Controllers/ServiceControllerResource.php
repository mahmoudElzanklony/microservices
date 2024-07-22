<?php

namespace App\Http\Controllers;

use App\Filters\NameFilter;
use App\Filters\sections\SectionIdFilter;
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
        $this->middleware('auth:sanctum')->except('index','show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = services::query()->orderBy('id','DESC');
        $output  = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class,
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
