<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCode;
use App\Mail\VerificationCode;
use Laravel\Ui\Presets\React;
use App\VehicleRequest;


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
            'profile' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message' => $error]);
        }

        if ($request->user_type == 1) {
            $validator = Validator::make($request->all(), [
                'company' => 'required',
                'location' => 'required',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return response()->json(['status' => 202, 'message' => $error]);
            }
        }



        $check_email = User::where(['email' => $request->email])->first();

        if (!empty($check_email)) {
            return response()->json(['status' => 203, 'message' => 'Email Already Exist']);
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
        $imageName = "";
        if ($request->hasFile('profile')) {
            $imageName = time() . '.' . $request->profile->extension();

            $request->profile->move(public_path('profiles'), $imageName);
            $create_user['profile'] = 'profiles/' . $imageName;
        }

        $user = User::create($create_user);
        $user =  Auth::loginUsingId($user->id);

        if ($user->user_type == 2) {
            $user->rating = 0;
        }

        $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;
        return response()->json(['status' => 200, 'message' => 'Registration successful', 'data' => $user]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message' => $error]);
        }

        $credentilas = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentilas)) {
            $user = Auth::user();
            if ($user->is_verified == 0) {
                $six_digit_random_number = mt_rand(1000, 9999);
                Mail::to($request->email)->send(new VerificationCode($six_digit_random_number));
                User::where(['email' => $request->email])->update(['otp' => Hash::make($six_digit_random_number)]);
                return response()->json(['status' => 202, 'message' => 'Your email is not verified']);
            }

            if ($user->user_type == 2) {
                $vehicle_requests = VehicleRequest::where(['valet' => $user->id])->pluck('id');
                $rating = Feedback::whereIn('request_id', $vehicle_requests)->avg('rating');
                $user->rating = $rating == null ? 0 : $rating;
            }
            $user = User::where(['id' => $user->id])->first();
            $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;
            User::where(['id' => $user->id])->update(['first_login' => 1]);
            return response()->json(['status' => 200, 'message' => 'Login Successfully', 'data' => $user]);
        } else {
            return response()->json(['status' => 204, 'message' => 'Invalid Email Or Password']);
        }
    }

    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message' => $error]);
        }

        $check_email = User::where(['email' => $request->email])->first();

        if (empty($check_email)) {
            return response()->json(['status' => 203, 'message' => 'Email Not Found']);
        }

        $six_digit_random_number = mt_rand(1000, 9999);
        Mail::to($request->email)->send(new ResetPasswordCode($six_digit_random_number));
        User::where(['email' => $request->email])->update(['otp' => Hash::make($six_digit_random_number)]);
        return response()->json(['status' => 200, 'message' => 'Verification Code send to your email', 'OTP' => $six_digit_random_number]);
    }


    public function verify_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message' => $error]);
        }

        $user = User::where(['email' => $request->email])->first();

        if (empty($user)) {
            return response()->json(['status' => 203, 'message' => 'Invalid Email']);
        }

        if (Hash::check($request->otp, $user->otp)) {
            $user = User::where(['email' => $request->email])->update(['otp' => '', 'is_verified' => 1]);
            return response()->json(['status' => 200, 'message' => 'OTP Verified Successfully']);
        } else {
            return response()->json(['status' => 203, 'message' => 'Invalid OTP']);
        }
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'new_password' => 'required|same:confirm_password',
            'confirm_password' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message' => $error]);
        }

        User::where(['email' => $request->email])->update(['password' => Hash::make($request->new_password)]);
        return response()->json(['status' => 200, 'message' => 'Password Updated Successfully']);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
        }
        return response()->json(['status' => 200, 'message' => 'logout successfully']);
    }

    public function profile()
    {
        $user = Auth::user();
        return response()->json(['status' => 200, 'message' => 'Profile', 'data' => $user]);
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message' => $error]);
        }

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
                return response()->json(['status' => 202, 'message' => $error]);
            }

            $update_user['company'] = $request->company;
            $update_user['location'] = $request->location;
        }

        $check_email = User::where(['email' => $request->email])->where('id', '!=', Auth::user()->id)->first();

        if (!empty($check_email)) {
            return response()->json(['status' => 203, 'message' => 'Email Already Exist']);
        }


        if ($request->hasFile('profile')) {
            $imageName = time() . '.' . $request->profile->extension();

            $request->profile->move(public_path('profiles'), $imageName);
            $update_user['profile'] = 'profiles/' . $imageName;
        }

        if (isset($request->password)) {
            $update_user['password'] = Hash::make($request->password);
        }

        User::where(['id' => Auth::user()->id])->update($update_user);
        return response()->json(['status' => 200, 'message' => 'Profile Updated Succefully', 'data' => Auth::user()]);
    }
}
