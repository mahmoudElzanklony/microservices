<?php

namespace App\Http\Controllers;

use App\Http\Requests\sectionAttributeFormRequest;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\SectionAttributeResource;
use App\Models\attributes;
use App\Models\attributes_sections;
use App\Services\Messages;
use Illuminate\Http\Request;

class SectionsAttributesControllerResource extends Controller
{
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

    public function store(sectionAttributeFormRequest $request)
    {
        //
        $data = $request->validated();
        return $this->save($data);
    }


    public function save($data)
    {
        $output = attributes_sections::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        return Messages::success(__(trans('messages.saved_successfully')),SectionAttributeResource::make($output));
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
    public function update(sectionAttributeFormRequest $request, string $id)
    {
        $data = $request->validated();
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
