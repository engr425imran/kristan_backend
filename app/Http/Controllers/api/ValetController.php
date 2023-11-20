<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerificationCode;
use App\User;
use App\VehicleRequest;
use App\DistributedTip;
use App\Feedback;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class ValetController extends Controller
{
    public function create_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }

        $user = User::where(['email'=> $request->email])->first();

        if(empty($user))
        return response()->json(['status' => 203, 'message'=> 'Invalid Email']);

        User::where(['email'=> $request->email])->update(['password'=> Hash::make($request->password)]);

        $user =  Auth::loginUsingId($user->id);

        $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;
        return response()->json(['status' => 200, 'message' => 'Password Created', 'data'=> $user]);
    }

    public function get_vehicle_request()
    {
        $request = VehicleRequest::where(['valet'=> Auth::user()->id])->orderBy('id', 'DESC')->first();
        return response()->json(['status'=>200 , 'message'=> 'New Vehicle Request', 'data'=> $request]);
    }

    public function accept_reject_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'response' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }

        if($request->response == 0)
        {
            $vehicle_request = VehicleRequest::where(['id'=>$request->request_id])->first();
            VehicleRequest::where(['id' => $request->request_id])->update(['valet' => NULL]);
            if(!empty($vehicle_request))
            {
                User::where(['id'=> $vehicle_request->valet])->update(['is_free'=> 1]);
            }

            $message = 'Request Declined Successfully';
        } else {
            $message = 'Request Accepted Successfully';
        }

        return response()->json(['status' => 200, 'message'=> $message]);
    }

    public function request_completed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }
        $vehicle_request = VehicleRequest::where(['id'=>$request->request_id])->first();
        if(!empty($vehicle_request))
        {
            User::where(['id'=> $vehicle_request->valet])->update(['is_free'=> 1]);
        }
        VehicleRequest::where(['id' => $request->request_id])->update(['status' => 1]);
        return response()->json(['status' => 200, 'messasge' => 'Request Completed']);
    }
    
     public function my_tips()
    {
        $request_ids = VehicleRequest::where(['valet'=> Auth::user()->id])->where(['status'=> 1])->pluck('id');
        $direct_tips = Feedback::whereIn('request_id', $request_ids)->where('tip_type',1)->sum('tip');
        $pooled_tip = Feedback::whereIn('request_id', $request_ids)->where('tip_type',0)->sum('tip');
        $direct = Feedback::whereIn('request_id', $request_ids)->select('tip','customer_id','created_at')->get();
        // $direct->total = $direct_tips;
        return response()->json(['status'=> 200, 'message'=> '', 'total_pooled_tip'=> $pooled_tip,'total_direct_tips'=>$direct_tips, 'direct_tips_detail'=> $direct]);
    }
}  
