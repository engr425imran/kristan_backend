<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\ValetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\api\ValetManagerController;
use  App\Http\Controllers\api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Get list of meetings.
Route::get('/meetings', 'Zoom\MeetingController@list');

// Create meeting room using topic, agenda, start_time.
Route::post('/meetings', 'Zoom\MeetingController@create');

// Get information of the meeting room by ID.
Route::get('/meetings/{id}', 'Zoom\MeetingController@get')->where('id', '[0-9]+');
Route::patch('/meetings/{id}', 'Zoom\MeetingController@update')->where('id', '[0-9]+');
Route::delete('/meetings/{id}', 'Zoom\MeetingController@delete')->where('id', '[0-9]+');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class,'forgot']);
Route::post('verify-otp', [AuthController::class,'verify_otp']);
Route::post('update-password', [AuthController::class,'update_password']);
Route::post('valet-create-password',[ValetController::class, 'create_password']);

Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function(){
    Route::post('add_valet',[ValetManagerController::class, 'add_valet']);
    Route::GET('all-locations',[ValetManagerController::class, 'locations']);
    Route::GET('all_valet',[ValetManagerController::class, 'get_all_valet']);
    Route::GET('all-vehicle-request',[ValetManagerController::class, 'all_vehicle_request']);
    Route::post('set_tip_type', [ValetManagerController::class, 'tip_type']);
    Route::post('assign-to-valet', [ValetManagerController::class, 'assign_to_valet']);
    Route::post('unassign-valet', [ValetManagerController::class, 'unassign_valet']);
    Route::POST('update-profile', [AuthController::class, 'update_profile']);
    Route::GET('profile', [AuthController::class, 'profile']);
    Route::GET('get/latest/feedback', [ValetManagerController::class, 'get_feedback']);
    Route::POST('send/greetings', [ValetManagerController::class, 'send_greetings']);
    Route::POST('feedback/reporting',[ValetManagerController::class, 'feedback_reporting']);
    Route::POST('tip/reporting',[ValetManagerController::class, 'tip_reporting']);
    Route::POST('update/contribution',[ValetManagerController::class, 'update_contribution']);
    Route::GET('distriubte',[ValetManagerController::class, 'distriubte']);
    Route::GET('payment/request',[ValetManagerController::class, 'payment_request']);
    Route::POST('update/payment/request',[ValetManagerController::class, 'update_payment_request']);


    Route::group(['prefix' => 'customer'], function(){
        Route::POST('vehicle/request', [CustomerController::class, 'vehicleRequest']);  
        Route::GET('cancel/request/{request_id?}', [CustomerController::class, 'cancel_request']);  
        Route::GET('all/cancel/requests', [CustomerController::class, 'all_cancel_request']);  
        Route::GET('undo/request/{request_id?}', [CustomerController::class, 'undo_request']);  
        Route::POST('give/feedback', [CustomerController::class, 'feedback']);  
        Route::GET('get/requests', [CustomerController::class, 'getRequests']);
        Route::GET('get/latest/request', [CustomerController::class, 'get_latest_request']);

  });

  Route::group(['prefix' => 'valet'], function(){
      Route::GET('get/vehicle/request',[ValetController::class, 'get_vehicle_request']);
      Route::POST('accept/reject/request',[ValetController::class, 'accept_reject_request']);
      Route::POST('request/completed',[ValetController::class, 'request_completed']);
      Route::GET('my/tips',[ValetController::class, 'my_tips']);
      Route::POST('payment/withdraw', [PaymentController::class, 'withdraw']);
  });

  Route::GET('logout', [AuthController::class, 'logout']);
  Route::POST('payment', [PaymentController::class, 'payment']);
});


