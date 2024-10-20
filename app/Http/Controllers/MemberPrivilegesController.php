<?php

namespace App\Http\Controllers;

use App\Http\Resources\ControllerPrivilegeResource;
use App\Http\Resources\ServicePrivilegeResource;
use App\Models\services_privileges;
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
}
