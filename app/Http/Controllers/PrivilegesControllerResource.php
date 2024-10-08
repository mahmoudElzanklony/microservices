<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrivilegeResource;
use App\Models\privileges;
use Illuminate\Http\Request;

class PrivilegesControllerResource extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->except('show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = privileges::query()->get();
        return PrivilegeResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
