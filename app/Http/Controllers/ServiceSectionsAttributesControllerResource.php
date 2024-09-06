<?php

namespace App\Http\Controllers;

use App\Http\Requests\serviceSecAttrFormRequest;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceSecAttrResource;
use App\Models\attributes;
use App\Models\services;
use App\Models\services_sections_data;
use App\Models\styles;
use App\Services\FormRequestHandleInputs;
use App\Services\Messages;
use Illuminate\Http\Request;

class ServiceSectionsAttributesControllerResource extends Controller
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
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function save($data)
    {
        $service = services::query()->find($data['service_id']);
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['main_title','sub_title']);
        $service->update([
            'main_title'=>$data['main_title'],
            'sub_title'=>$data['sub_title'],
        ]);
        $data['section_id'] = collect($data['section_id'])->unique()->values()->all();
        $data['attribute_id'] = collect($data['attribute_id'])->unique()->values()->all();
        foreach($data['section_id'] as $key => $sec_id){
            services_sections_data::query()->updateOrCreate([
                'id'=>$data['item_id'][$key] ?? null
            ],[
                'section_id'=>$sec_id,
                'attribute_id'=>$data['attribute_id'][$key],
                'service_id'=>$data['service_id'],
                'type'=>$data['type'][$key],
            ]);
        }
        $this->save_style($service,$data);
        $service->load('style');
        $service->load('sec_attr_data');
        return Messages::success(__(trans('messages.saved_successfully')),ServiceResource::make($service));

    }

    public function save_style($service,$data)
    {
        $style = new styles();
        $style->style = $data['style'];
        styles::query()->where('styleable_id','=',$service->id)
            ->where('styleable_type','=','App\Models\services')->delete();
        $service->style()->save($style);
    }
    public function store(serviceSecAttrFormRequest $request)
    {
        //
        $data = $request->validated();
        return $this->save($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $service = services::query()->with(['style','sec_attr_data'])->find($id);
        return ServiceResource::make($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(serviceSecAttrFormRequest $request, string $id)
    {
        //
        $data = $request->validated();
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
