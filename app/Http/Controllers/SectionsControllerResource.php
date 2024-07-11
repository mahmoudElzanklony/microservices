<?php

namespace App\Http\Controllers;

use App\Http\Requests\sectionFormRequest;
use App\Http\Resources\SectionResource;
use App\Models\sections;
use App\Services\FormRequestHandleInputs;
use App\Services\Messages;
use Illuminate\Http\Request;

class SectionsControllerResource extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = sections::query()->orderBy('id', 'desc')->get();
        return  SectionResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionFormRequest $request)
    {
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
    public function save($data)
    {
        if(!(array_key_exists('user_id',$data))){
            $data['user_id'] = auth()->id();
        }
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['name']);
        $output = sections::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        return Messages::success(__(trans('messages.saved_successfully')),SectionResource::make($output));
    }
    public function update(SectionFormRequest $request , string $id)
    {
        $data = $request->validated();
        $data['user_id'] = sections::query()
            ->where('id',$id)->FailIfNotFound(__('errors.not_found'))->user_id;
        if(!($data['user_id'] == auth()->id() || auth()->user()->role->name == 'admin')){
            return abort(403);
        }
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
