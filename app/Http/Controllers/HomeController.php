<?php

namespace App\Http\Controllers;

use App\Models\attributes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    //
    public function index()
    {


        $data = attributes::query()->with('user')->get()->count();
        return view('welcome')->with('data',$data);
    }
}
