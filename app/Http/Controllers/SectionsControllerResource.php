<?php

namespace App\Http\Controllers;

use App\Filters\EndDateFilter;
use App\Filters\NameFilter;
use App\Filters\sections\NoOwnerShipFilter;
use App\Filters\StartDateFilter;
use App\Filters\UserIdFilter;
use App\Filters\VisibilityFilter;
use App\Http\Requests\sectionFormRequest;
use App\Http\Resources\SectionResource;
use App\Models\sections;
use App\Services\FormRequestHandleInputs;
use App\Services\Messages;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;


class SectionsControllerResource extends Controller
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
        $data = sections::query()
            ->when(auth()->check() && auth()->user()->roleName() == 'admin',fn($e) => $e->with('user'))

            ->with('attributes.options')->orderBy('id', 'desc');

        $output  = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class,
                UserIdFilter::class,
                VisibilityFilter::class,
                NoOwnerShipFilter::class,
                StartDateFilter::class,
                EndDateFilter::class,
            ])
            ->thenReturn()
            ->paginate(request('limit') ?? 10);
        return  SectionResource::collection($output);
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
        if(isset($data['attributes'])){
            $output->attributes()->sync($data['attributes']);
        }
        $output->load('attributes');
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
