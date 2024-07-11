<?php

namespace App\Http\Controllers;

use App\Filters\EndDateFilter;
use App\Filters\NameFilter;
use App\Filters\sections\SectionIdFilter;
use App\Filters\StartDateFilter;
use App\Filters\users\UserNameFilter;
use App\Filters\users\WalletFilter;
use App\Http\Requests\attributeFormRequest;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\SectionResource;
use App\Models\attributes;
use App\Models\sections;
use App\Services\FormRequestHandleInputs;
use App\Services\Messages;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class AttributesControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index','show');
    }
    public function index()
    {
        $data = attributes::query();
        $output  = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class,
                SectionIdFilter::class
            ])
            ->thenReturn()
            ->paginate(request('limit') ?? 10);
        return AttributeResource::collection($output);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(attributeFormRequest $request)
    {
        $data = $request->validated();
        return $this->save($data);
    }

    public function save($data)
    {
        if(!(array_key_exists('user_id',$data))){
            $data['user_id'] = auth()->id();
        }
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['label','placeholder']);
        $output = attributes::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        return Messages::success(__(trans('messages.saved_successfully')),AttributeResource::make($output));
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
    public function update(attributeFormRequest $request, string $id)
    {
        //
        $data =  $request->validated();
        $data['id'] = $id;
        $data['user_id'] = attributes::query()
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
