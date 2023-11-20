<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\VehicleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Feedback;

class CustomerController extends Controller
{
    public function vehicle_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_number' => 'required',
            'location' => 'required',
            'duration' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }

        $valet_manger = User::where(['location'=> $request->location, 'user_type'=> 1])->first();

        if(empty($valet_manger))
        return response()->json(['status'=> 203, 'message'=> 'invalid location']);

        $request_data = [
            'customer_id' => Auth::user()->id,
            'ticket_number' => $request->ticket_number,
            'location' => $request->location,
            'duration'=> date("H:i:s", strtotime($request->duration)),
            'valet_manager' => $valet_manger->id,
        ];

        $request_data = VehicleRequest::create($request_data);
        return response()->json(['status'=> 200, 'message'=> 'Request send successfullly to valet manager', 'data'=> $request_data]);
    }

    public function cancel_request($request_id = NULL)
    {
        if($request_id == NULL)
        return response()->json(['status'=> 202, 'message'=> 'Please send request id']);

        $request = VehicleRequest::where(['id'=> $request_id, 'customer_id'=> Auth::user()->id])->first();
        if(!empty($request))
        {
            if($request->status == 1)
            {
                return response()->json(['status'=> 203, 'message'=> 'You can not cancel this request']);
            }

        } else {
            return response()->json(['status'=> 203, 'message'=> 'Invalid Request']);
        }

        VehicleRequest::where(['id'=> $request_id, 'customer_id'=> Auth::user()->id])->update(['status' => 2, 'valet' => NULL]);
        return response()->json(['status'=> 200, 'message'=> 'Request Canceled Successfully']);
    }

    public function all_cancel_request()
    {
        $canceled_requests = VehicleRequest::where(['customer_id'=> Auth::user()->id])->where(['status' => 2])->get();
        return response()->json(['status'=>200, 'message'=> 'All Cancelled Requests', 'data'=> $canceled_requests]);
    }

    public function undo_request($request_id = NULL)
    {
        if($request_id == NULL)
        return response()->json(['status'=> 202, 'message'=> 'Please send request id']);

        VehicleRequest::where(['id'=> $request_id, 'customer_id'=> Auth::user()->id])->update(['status' => 0, 'valet' => NULL]);
        return response()->json(['status'=> 200, 'message'=> 'Request Undo Successfully']);
    }

    public function feedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'rating' => 'required',
            'message' => 'required',
            'tip' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }
        $vehicle_request = VehicleRequest::where(['id'=> $request->request_id])->first();
        if(empty($vehicle_request))
        {
            return response()->json(['status'=> 203, 'message'=> 'Invalid Vehicle Request Id']);
        }

        $valet_manger = User::where(['id'=> $vehicle_request->valet_manager])->first();
        if($valet_manger->tip_type == 0) // pooled tip
        {
            User::where(['id' => $valet_manger->id])->update(['total_tip'=> $valet_manger->total_tip + $request->tip]);
        } else {
            User::where(['id' => $vehicle_request->valet])->update(['total_tip'=> $valet_manger->total_tip + $request->tip]);
        }

        $feedback = [
            'customer_id' => Auth::user()->id,
            'request_id' => $request->request_id,
            'rating' => $request->rating,
            'message' => $request->message,
            'tip_type' => $valet_manger->tip_type,
            'tip' => $request->tip,
        ];

        Feedback::create($feedback);

        return response()->json(['status'=> 200, 'message'=> 'feedback submitted successfully']);
    }
    
    public function get_requests()
    {
       $requests = VehicleRequest::where(['customer_id'=> Auth::user()->id, 'status'=> 1])->with('feedback')->get();
       return response()->json(['status'=> 200, 'message'=> 'All Completed Requests', 'data'=> $requests]);
    }
     public function get_latest_request()
    {
        $requests = VehicleRequest::where(['customer_id'=> Auth::user()->id])->orderBy('id', 'DESC')->first();
        return response()->json(['status'=> 200, 'message'=> 'Latest Request', 'data'=> $requests]);
    }
}
