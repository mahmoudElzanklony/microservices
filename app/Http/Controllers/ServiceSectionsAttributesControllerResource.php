<?php

namespace App\Http\Controllers;

use App\Actions\CheckEmailExistAtService;
use App\Http\Enum\ServiceTypeEnum;
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

        $data['attribute_id'] = collect($data['attribute_id'])->unique()->values()->all();

        // Fetch the existing attribute IDs for the given service_id
        $existingAttributes = services_sections_data::query()
            ->where('service_id', '=', $data['service_id'])
            ->pluck('attribute_id')
            ->toArray();


// Get the difference between the provided attribute IDs and the existing attribute IDs
        $attributesToRemove = collect($existingAttributes)->diff($data['attribute_id']);
        //return [$attributesToRemove,$existingAttributes];

// Remove the records that match the attribute IDs to remove
        if ($attributesToRemove->isNotEmpty()) {
            services_sections_data::query()->where('service_id', $data['service_id'])
                ->whereIn('attribute_id', $attributesToRemove)
                ->delete();
        }

        foreach($data['attribute_id'] as $key => $val){
            services_sections_data::query()->updateOrCreate([
                'id'=>$data['item_id'][$key] ?? null
            ],[
                'attribute_id'=>$val,
                'service_id'=>$data['service_id'],
                'type'=>$data['types'][$key],
            ]);
        }
        $this->save_style($service,$data);
        $service->load('style');
        $service->load('sec_attr_data');
        return Messages::success(__(trans('messages.saved_successfully')),ServiceResource::make($service));

    }


    public function save_service($data,$other_data = [])
    {
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['main_title','sub_title']);

        services::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);

        if($data['type'] == ServiceTypeEnum::in_mail->value){
            // validate if email in attribute or not
            CheckEmailExistAtService::check($other_data);

        }


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
        $this->save_service(request()->only('id','name','type','service_id'
            ,'ar_main_title','en_main_title','ar_sub_title','en_sub_title'),$data);
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
        $this->save_service(request()->only('id','name','type','service_id'
            ,'ar_main_title','en_main_title','ar_sub_title','en_sub_title'),$data);
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
