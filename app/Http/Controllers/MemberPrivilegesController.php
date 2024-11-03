<?php

namespace App\Http\Controllers;

use App\Http\Requests\assignPrivilegesFormRequest;
use App\Http\Resources\ControllerPrivilegeResource;
use App\Http\Resources\ServicePrivilegeResource;
use App\Models\privileges;
use App\Models\services;
use App\Models\services_privileges;
use App\Models\services_privileges_controls;
use App\Services\Messages;
use Illuminate\Http\Request;

class MemberPrivilegesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    //
    public function index()
    {
        $data = services_privileges::activePrivileges(request('service_id'))
            ->with('privileges')
            ->first();

        return ServicePrivilegeResource::make($data);
    }

    public function assign_members(assignPrivilegesFormRequest $request)
    {
        $data = $request->validated();
        $service = services::query()->find($data['service_id']);
        services_privileges::query()->where('service_id',$data['service_id'])->delete();
        $all_privileges = privileges::query()->get();
        foreach ($data['privileges'] as $item){
            if(isset($item['privilege'])){
                $service_privilege = services_privileges::query()
                    ->create(['user_id'=>$item['user_id'],'service_id'=>$data['service_id']]);
                foreach ($item['privilege'] as $k => $priv) {
                    services_privileges_controls::query()->create([
                        'service_privilege_id'=>$service_privilege->id,
                        'privilege_id' => $all_privileges->first(fn($e) => $e->name == $priv)->id
                    ]);
                }

            }
        }
        return Messages::success(__('messages.saved_successfully'));
    }
}
