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
use App\Transaction;

class ValetController extends Controller
{
    public function SignUpValet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(["message" => $error], 400);
        }

        $user = User::where(['email' => $request->email])->first();

        if (empty($user))
            return response(["message" => 'Invalid Email'], 400);
        User::where(['email' => $request->email])->update(['password' => Hash::make($request->password)]);
        $user =  Auth::loginUsingId($user->id);

        $user->token = $user->createToken('Laravel Password Grant Client')->accessToken;
        return response(["message" => 'Password Created', 'data' => $user], 200);
        // return response()->json(['status' => 200, 'message' => 'Password Created', 'data'=> $user]);
    }

    public function getVehiclesRequest()
    {
        $user_id = Auth::user()->id;
        $request = VehicleRequest::where(['valet' => $user_id, 'status' => 0])->first();
        $feedback = Feedback::where(['valet_id' => $user_id, 'read_by_valet' => 0])->with('vehicle_request')->first();
        // $feedback = Feedback::where(['valet_id' => $user_id, 'read_by_valet' => 0])->with('vehicle_request')->first();
        return response(['request' => $request, 'feedback' => $feedback], 200);
    }

    public function acceptRejectRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(["message" => $error], 400);
        }

        $vehicle_request = VehicleRequest::where(['id' => $request->request_id])->first();
        if ($request->status == "Reject") {
            $vehicle_request->valet = null;
            if (!empty($vehicle_request)) {
                User::where(['id' => $vehicle_request->valet])->update(['is_free' => 1]);
            }
            $vehicle_request->save();
            $message = 'Request Declined Successfully';
            return response(["message" => $message,], 200);
        }
        if ($request->status == "Accept") {
            $vehicle_request->request_status = 1;
            $vehicle_request->save();
            $message = 'Request Accepted Successfully';
        }
        return response(["data" => $vehicle_request], 200);
    }


    public function requestCompleted(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(["message" => $error], 400);
        }
        $vehicle_request = VehicleRequest::where(['id' => $request->request_id])->first();
        if (!empty($vehicle_request)) {
            User::where(['id' => $vehicle_request->valet])->update(['is_free' => 1]);
        }
        $vehicle_request->status = 1;
        $vehicle_request->processed = 1;
        $vehicle_request->save();
        return response(["message" => 'Request Completed', 'data' => $vehicle_request], 200);
    }

    public function valetFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'greetings' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(["message" => $error], 400);
        }
        $vehicle_request = Feedback::where(['id' => $request->request_id])->first();
        if (!empty($vehicle_request)) {
            $vehicle_request->greetings = $request->greetings;
            $vehicle_request->read_by_valet = 1;
            $vehicle_request->save();
            return response(["message" => 'Request Completed', 'data' => $vehicle_request], 200);
        }
        return response(["message" => 'Record Not Found'], 404);
    }

    public function myTips()
    {
        $user = auth()->user();


        $request_ids = VehicleRequest::where(['valet' => Auth::user()->id])->where(['status' => 1, 'payment_done_by_customer' => 1])->pluck('id');
        $direct_tips = Feedback::whereIn('request_id', $request_ids)->where('tip_type', 1)->sum('tip');
        $pooled_tip = Feedback::whereIn('request_id', $request_ids)->where('tip_type', 0)->sum('tip');
        $transfer_rem = Transaction::where('valet', $user->id)->where('status', 0)->count();
        // $pooled_tip = Transaction::whereIn('id', $user->id)->where('tip_type', 0)->sum('tip');
        // $direct = Feedback::whereIn('request_id', $request_ids)->select('tip', 'customer_id', 'created_at')->get();
        // $direct->total = $direct_tips;

        return response(['pooled_tip' => $pooled_tip, 'direct_tips' => $direct_tips, 'total_tip' => $user->total_tip, 'transfer_remain' => $transfer_rem], 200);
        // return response(['total_pooled_tip' => $pooled_tip, 'total_direct_tips' => $direct_tips, 'direct_tips_detail' => $direct]);
    }
}
