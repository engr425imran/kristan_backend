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
Route::post('forgot-password', [AuthController::class, 'sendforgotPasswordMail']);
Route::post('verify-otp', [AuthController::class, 'verify_otp']);
Route::post('update-password', [AuthController::class, 'updatePassword']);
Route::post('valet-create-password', [ValetController::class, 'SignUpValet']);

Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function () {

    //*************************** */ valet manager *************************** //
    Route::post('add_valet', [ValetManagerController::class, 'addNewValet']);
    Route::GET('get-all-locations', [ValetManagerController::class, 'getALllocations']);
    Route::GET('get-all-valet', [ValetManagerController::class, 'getAllValet']);
    Route::GET('all-vehicle-request', [ValetManagerController::class, 'getAllVehicleRequest']);
    Route::post('set-tip-distribution-type', [ValetManagerController::class, 'updateTipType']);
    Route::post('assign-to-valet', [ValetManagerController::class, 'assignToValet']);
    Route::post('unassign-valet', [ValetManagerController::class, 'unassign_valet']);
    Route::POST('update-profile', [AuthController::class, 'updateProfile']);
    Route::GET('user-profile-display', [AuthController::class, 'displayProfile']);
    // Route::GET('profile-display-user/{profile}', [AuthController::class, 'displayProfile']);
    Route::GET('get/latest/feedback', [ValetManagerController::class, 'get_feedback']);
    Route::POST('send/greetings', [ValetManagerController::class, 'send_greetings']);
    Route::POST('valet-rating-report', [ValetManagerController::class, 'valetRatingReport']);
    Route::POST('valet-tip-report', [ValetManagerController::class, 'valetTipReport']);
    Route::POST('update/contribution', [ValetManagerController::class, 'update_contribution']);
    Route::GET('distriubte', [ValetManagerController::class, 'distriubte']);
    Route::GET('payment/request', [ValetManagerController::class, 'payment_request']);
    Route::POST('update/payment/request', [ValetManagerController::class, 'update_payment_request']);


    //*************************** */  ..... end ..... *************************** //

    Route::group(['prefix' => 'customer'], function () {
        Route::POST('vehicle/request', [CustomerController::class, 'CreateVehicleRequest']);
        Route::post('send-tip', [PaymentController::class, 'newImple']);
        Route::post('save-payment-details', [CustomerController::class, 'completePayment']);
        Route::GET('cancel-request/{request_id?}', [CustomerController::class, 'cancelRequest']);
        Route::GET('all/cancel/requests', [CustomerController::class, 'all_cancel_request']);
        Route::GET('undo/request/{request_id?}', [CustomerController::class, 'undo_request']);
        Route::POST('give-feedback', [CustomerController::class, 'feedback']);
        Route::POST('tip-valet', [PaymentController::class, 'paymentIntent']);
        Route::GET('get/requests', [CustomerController::class, 'getRequests']);
        Route::GET('get/latest/request', [CustomerController::class, 'get_latest_request']);
    });

    Route::group(['prefix' => 'valet'], function () {
        Route::GET('get-vehicle-request', [ValetController::class, 'getVehiclesRequest']);
        Route::POST('request-status', [ValetController::class, 'acceptRejectRequest']);
        Route::POST('request-completed', [ValetController::class, 'requestCompleted']);
        Route::GET('my-tips', [ValetController::class, 'myTips']);
        Route::POST('feedback-customer', [ValetController::class, 'valetFeedback']);
        Route::POST('payment-withdraw', [PaymentController::class, 'withdrawValetRequest']);
    });

    Route::GET('logout', [AuthController::class, 'logout']);
    Route::POST('payment', [PaymentController::class, 'payment']);
});
Route::get('test', [PaymentController::class, 'test']);
Route::get('intent', [PaymentController::class, 'intent']);
Route::post('charge', [PaymentController::class, 'paymentt']);
Route::get('token', [PaymentController::class, 'token']);

Route::get('ll', function () {
    $t = strtotime('About 15 minutes');
    return $t;
    $duration = date("H:i:s", strtotime('About 15 minutes'));
    // $duration = date("H:i:s", strtotime($request->duration));
    return $duration;
});

Route::post('test-send-tip', [PaymentController::class, 'newImple']);
Route::get('dd', function () {
    // return "Sss";
    return App\VehicleRequest::find(18);
});
