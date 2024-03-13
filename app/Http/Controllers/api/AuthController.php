<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCode;
use App\Mail\VerificationCode;
use Laravel\Ui\Presets\React;
use App\VehicleRequest;
use App\Jobs\SendVerificationOtp;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|int',
            // 'profile' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }



        if ($request->user_type == 1) {
            $validator = Validator::make($request->all(), [
                'company' => 'required',
                'location' => 'required',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return response(["message" => $error], 400);
            }
        }


        $check_email = User::where(['email' => $request->email])->first();

        if (!empty($check_email)) {
            return response(['message' => 'Email Already Exist'], 409);
        }
        $create_user = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'company' => (isset($request->company)) ? $request->company : '',
            'location' => (isset($request->location)) ? $request->location : '',
        ];

        if ($request->hasFile('profile')) {
            $image_name = time() . '.' . $request->profile->extension();
            $path = Storage::putFileAs(
                'profiles',
                $request->file('profile'),
                $image_name
            );

            $create_user['profile'] = $path;
        }

        $user = User::create($create_user);
        $user =  Auth::loginUsingId($user->id);

        if ($user->user_type == 2) {
            $user->rating = 0;
        }
        $resetPassword = false;
        $this->sendOtp($request->email, $resetPassword);
        return response(['message' => 'otp sent'], 200);

        // $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;
        // return response()->json(['status' => 200, 'message' => 'Registration successful', 'data' => $user]);
    }

    public function sendOtp($email, $resetPassword)
    {
        try {
            $code = mt_rand(1000, 9999);
            User::where(['email' => $email])->update(['otp' => Hash::make($code)]);
            SendVerificationOtp::dispatch($email, $code, $resetPassword);
        } catch (\Throwable $th) {
            // throw $th;
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);



        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }


        $credentilas = [
            'email' => $request->email,
            'password' => $request->password
        ];


        if (Auth::attempt($credentilas)) {
            $user = Auth::user();
            if (!$user->email_verified_at) {
                if (!$user->otp) {
                    $resetPassword = false;
                    $this->sendOtp($user->email, $resetPassword);
                    return response(['message' => 'Your email is not verified'], 403);
                }
                return response(['message' => 'otp has been sent already'], 401);
            }

            // if ($user->is_verified == 0) {
            //     $six_digit_random_number = mt_rand(1000, 9999);
            //     Mail::to($request->email)->send(new VerificationCode($six_digit_random_number));
            //     User::where(['email' => $request->email])->update(['otp' => Hash::make($six_digit_random_number)]);
            //     return response()->json(['status' => 403, 'message' => 'Your email is not verified']);
            // }

            // if ($user->user_type == 2) {
            //     $vehicle_requests = VehicleRequest::where(['valet' => $user->id])->pluck('id');
            //     return $vehicle_requests;
            //     $rating = Feedback::whereIn('request_id', $vehicle_requests)->avg('rating');
            //     $user->rating = $rating == null ? 0 : $rating;
            // }


            $user = User::where(['id' => $user->id])->first();
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            // $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;
            User::where(['id' => $user->id])->update(['first_login' => 1]);
            return response(['message' => 'Login Successfully', 'user' => $user, 'token' => $token], 200);
        } else {
            return response(['message' => 'Either email or password is incorect ...'], 404);
        }
    }

    public function sendforgotPasswordMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }

        $check_email = User::where(['email' => $request->email])->first();

        if (empty($check_email)) {
            return response(['message' => 'Email Not Found'], 404);
        }
        $resetPassword = true;
        $this->sendOtp($request->email, $resetPassword);
        // $six_digit_random_number = mt_rand(1000, 9999);
        // Mail::to($request->email)->send(new ResetPasswordCode($six_digit_random_number));
        // User::where(['email' => $request->email])->update(['otp' => Hash::make($six_digit_random_number)]);
        return response(['message' => 'Verification Code send to your email'], 200);
    }


    public function verify_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }

        $user = User::where(['email' => $request->email])->first();

        if (empty($user)) {
            return response(['message' => 'Invalid Email'], 400);
        }

        if (Hash::check($request->otp, $user->otp)) {
            $user = User::where(['email' => $request->email])->update(['otp' => '', 'email_verified_at' => now()]);
            return response(['message' => 'OTP Verified Successfully'], 200);
        } else {
            return response(['message' => 'Invalid OTP Provided'], 404);
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'new_password' => 'required|same:confirm_password',
            'confirm_password' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }

        User::where(['email' => $request->email])->update(['password' => Hash::make($request->new_password)]);
        return response(['message' => 'Password Updated Successfully'], 200);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
        }
        return response(['message' => 'logout successfully'], 200);
    }

    public function displayProfile()
    {
        $user = auth()->user();
        if (Storage::exists($user->profile)) {
            return Storage::download($user->profile);
        }
        return response(["message" => 'not found'], 404);
    }

    public function updateProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }
        $user = auth()->user();
        $update_user = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ];

        if (Auth::user()->user_type == 1) {
            $validator = Validator::make($request->all(), [
                'company' => 'required',
                'location' => 'required',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return response(['message' => $error], 400);
            }

            $update_user['company'] = $request->company;
            $update_user['location'] = $request->location;
        }

        $check_email = User::where(['email' => $request->email])->where('id', '!=', Auth::user()->id)->first();

        if (!empty($check_email)) {
            return response(['message' => 'Email Already Exist'], 409);
        }

        if ($request->hasFile('profile')) {
            if ($user->profile) {
                if (Storage::exists($user->profile)) {
                    Storage::delete($user->profile);
                }
            }
            $image_name = time() . '.' . $request->profile->extension();
            $path = Storage::putFileAs(
                'profiles',
                $request->file('profile'),
                $image_name
            );

            $update_user['profile'] = $path;
        }


        // if ($request->hasFile('profile')) {
        //     $imageName = time() . '.' . $request->profile->extension();
        //     $path = Storage::putFile('avatars', $request->file('profile'));


        //     // $request->profile->move(public_path('profiles'), $imageName);
        //     $update_user['profile'] = 'profiles/' . $imageName;
        // }

        if (isset($request->password)) {
            $update_user['password'] = Hash::make($request->password);
        }

        User::where(['id' => Auth::user()->id])->update($update_user);
        $user = User::find(Auth::user()->id);
        // $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response(['message' => 'Profile Updated Succefully', 'user' => $user], 200);
    }
}
