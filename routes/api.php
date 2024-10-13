<?php

use App\Http\Controllers\AttributesControllerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ActivationAccountController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\CategoriesControllerResource;

use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\RatesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneralServiceController;
use App\Http\Controllers\SectionsControllerResource;
use App\Http\Controllers\SectionsAttributesControllerResource;
use App\Http\Controllers\ServiceControllerResource;
use App\Http\Controllers\ServiceSectionsAttributesControllerResource;
use App\Http\Controllers\ClientsServicesAnswersController;
use App\Http\Controllers\MemebersControllerResource;
use App\Http\Controllers\PrivilegesControllerResource;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware'=>'changeLang'],function (){
    // auth module
    Route::group(['prefix'=>'/auth'],function (){
        Route::post('/login',[LoginController::class,'login']);
        Route::post('/activate-account',[ActivationAccountController::class,'index']);
        Route::post('/register',[RegisterController::class,'register']);
        Route::post('/forget-password',[ForgetPasswordController::class,'index']);
        Route::post('/new-password',[ForgetPasswordController::class,'new_password']);
        Route::get('/otp-check',[ForgetPasswordController::class,'otp_check']);
        Route::post('/logout',[LoginController::class,'logout']);
    });

    // rates
    Route::group(['prefix'=>'/rates','middleware'=>'auth:sanctum'],function (){
        Route::post('/create',[RatesController::class,'create']);
    });
    // notifications
    Route::group(['prefix'=>'/notifications','middleware'=>'auth:sanctum'],function (){
        Route::get('/',[NotificationsController::class,'index']);
        Route::post('/read-at',[NotificationsController::class,'seen']);
    });

    // clients answers
    Route::group(['prefix'=>'/services-clients'],function (){
        Route::get('/privileges',[ClientsServicesAnswersController::class,'privileges']);
        Route::get('/',[ClientsServicesAnswersController::class,'index']);
        Route::post('/authorize',[ClientsServicesAnswersController::class,'authorize_user']);
        Route::post('/save-answers',[ClientsServicesAnswersController::class,'save_answers']);
    });

    // profile
    Route::group(['prefix'=>'/profile','middleware'=>'auth:sanctum'],function (){
        Route::post('/update-info',[ProfileController::class,'update_info']);
    });
    // admin panel
    Route::group(['prefix'=>'/dashboard','middleware'=>'auth:sanctum'],function (){
        Route::get('/users',[DashboardController::class,'users']);
        Route::get('/orders-statistics',[DashboardController::class,'orders']);
        Route::get('/orders-summary',[DashboardController::class,'orders_summary']);
        Route::post('/update-tax',[DashboardController::class,'update_tax']);
        Route::get('/get-tax',[DashboardController::class,'get_tax']);
        Route::group(['prefix'=>'/notifications-schedule','middleware'=>'auth:sanctum'],function (){
            Route::post('/save',[DashboardController::class,'create_notification_content']);
        });
    });
    // resources
    Route::resources([
        'sections'=>SectionsControllerResource::class,
        'attributes'=>AttributesControllerResource::class,
        'sections-attributes'=>SectionsAttributesControllerResource::class,
        'services'=>ServiceControllerResource::class,
        'services-sec-attrs'=>ServiceSectionsAttributesControllerResource::class,
        'members'=>MemebersControllerResource::class,
        'privileges'=>PrivilegesControllerResource::class
    ]);

    Route::post('/deleteitem',[GeneralServiceController::class,'delete_item']);

});

