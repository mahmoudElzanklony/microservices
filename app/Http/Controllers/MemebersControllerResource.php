<?php

namespace App\Http\Controllers;

use App\Actions\SaveMemberAction;
use App\Actions\SavePrivilegesAction;
use App\Filters\EndDateFilter;
use App\Filters\StartDateFilter;
use App\Filters\users\UserNameFilter;
use App\Http\Requests\memberFormRequest;
use App\Http\Requests\userFormRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Messages;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class MemebersControllerResource extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = User::query()
            ->with('services_privileges',fn($e)=>$e->with(['controls.privilege','service']))
            ->when(auth()->user()->roleName() != 'admin',fn($q) => $q->where('added_by',auth()->id()))
            ->orderBy('id','DESC')
            ->where('added_by','=',auth()->id());
        $output  = app(Pipeline::class)
            ->send($data)
            ->through([
                StartDateFilter::class,
                EndDateFilter::class,
                UserNameFilter::class
            ])
            ->thenReturn()
            ->paginate(request('limit') ?? 10);
        return UserResource::collection($output);
    }

    public function save($data , $items)
    {
        DB::beginTransaction();
        $data['added_by'] = auth()->id();

        $user = SaveMemberAction::save($data,'member');
        SavePrivilegesAction::save($user,$items);

        $user->load('services_privileges.controls.privilege');
        $user->load('services_privileges.service');
        DB::commit();
        return Messages::success(__(trans('messages.saved_successfully')),UserResource::make($user));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(memberFormRequest $request)
    {
        $data = request()->except('item');
        if($data['password'] == ''){
            unset($data['password']);
        }
        return $this->save($data,request('item'));

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
    public function update(memberFormRequest $request, string $id)
    {
        $data = request()->except('item');
        $data['id'] = $id;
        return $this->save($data,request('item'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
