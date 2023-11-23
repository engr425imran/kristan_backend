<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Mail\TestEmail;
use App\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = User::find(1)->toArray();
    // print_r($user['email']);die;
    // Mail::send('emails.test', $user, function ($message) use ($user) {
    // $message->to('zubairbasharat14@gmail.com');
    // $message->subject('Sendgrid Testing');
    // });
    return view('admin.login');
});
// Route::get('/admin/vendor', function () {
//     return view('admin.vendor');
// });


// Route::get('/admin/addVendors', function () {
//     return view('admin.add_vendor');
// });
// Route::get('/admin/addUsers', function () {
//     return view('admin.add_users');
// });

// Route::get('/admin/addUsers/valet/{id}', function () {
//     return view('admin.edit_users');
// });
// Route::get('/admin/addUsers/customer/{id}', function () {
//     return view('admin.edit_customer');
// });
// Route::get('/admin/addVendors/{id}', function () {
//     return view('admin.edit_vendors');
// });
// Route::get('/admin/addUsers/manager/{id}', function () {
//     return view('admin.edit_valet');
// });
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('login', [AdminController::class, 'login']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users/manager', [AdminController::class, 'managers']);
    Route::get('/admin/users/valet', [AdminController::class, 'valets']);
    Route::get('/admin/users/customer', [AdminController::class, 'customers']);
    Route::get('/admin/cms/about', [AdminController::class, 'about']);
    Route::get('/admin/cms/faq', [AdminController::class, 'faq']);
    Route::get('/admin/cms/terms', [AdminController::class, 'terms']);
    Route::get('/admin/setting', [AdminController::class, 'setting']);
    Route::get('/admin/profile', [AdminController::class, 'profile']);
    Route::get('delete/user/{id}', [AdminController::class, 'delete']);
    Route::post('save-about', [AdminController::class, 'save_about']);
    Route::post('save-faq', [AdminController::class, 'save_faq']);
    Route::post('save-term', [AdminController::class, 'save_term']);
    Route::post('save-setting', [AdminController::class, 'save_setting']);
    Route::post('update-profile', [AdminController::class, 'update_profile']);
    Route::get('logout', [AdminController::class, 'logout']);
    Route::post('admin/report/admin/getReport', [AdminController::class, 'getReport']);
    Route::get('/admin/report/allUsers', function () {
        return view('admin.users_report');
    });

    Route::get('/admin/report/revenue', function () {
        return view('admin.revenue_report');
    });

    Route::get('/admin/report/ratings', function () {
        return view('admin.rating_report');
    });
});
