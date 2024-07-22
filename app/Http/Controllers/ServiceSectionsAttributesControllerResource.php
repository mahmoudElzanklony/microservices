<?php

namespace App\Http\Controllers;

use App\Http\Requests\serviceSecAttrFormRequest;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\ServiceSecAttrResource;
use App\Models\attributes;
use App\Models\services;
use App\Models\services_sections_data;
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
        $output = services_sections_data::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        return Messages::success(__(trans('messages.saved_successfully')),ServiceSecAttrResource::make($output));

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
