<?php

use App\Http\Controllers\Employees\CreateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Employees\IndexController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class,'index']);


Route::group(['prefix'=>'/employees'],function (){
   Route::get('/', IndexController::class);
   Route::post('/create', CreateController::class);
});

Route::get('/test-websocket', function () {
    // Trigger an event for testing
    \Illuminate\Support\Facades\Broadcast::channel('messages', function () {
        return true;
    });
    event(new \App\Events\MyWebSocketEvent('Hello from WebSocket!'));
    return 'WebSocket event sent!';
});

