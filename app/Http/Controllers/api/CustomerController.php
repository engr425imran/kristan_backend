<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\VehicleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Feedback;
use Carbon\Carbon;


class CustomerController extends Controller
{
    public function CreateVehicleRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_number' => 'required',
            'location' => 'required',
            'duration' => 'required',
        ]);


        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }

        $valet_manger = User::where(['location' => $request->location, 'user_type' => 1])->first();
        if (empty($valet_manger))
            return response(['message' => 'invalid location'], 400);
        // return Auth;

        $request_data = [
            'customer_id' => Auth::user()->id,
            'ticket_number' => $request->ticket_number,
            'location' => $request->location,
            // 'duration'=> date("H:i:s", strtotime($request->duration)),
            'duration' => $request->duration,
            // 'valet_manager' =>1,
            'valet_manager' => $valet_manger->id,
        ];


        $request_data = VehicleRequest::create($request_data);

        return response(['message' => 'Request send successfullly to valet manager', 'data' => $request_data], 201);
        // return response()->json(['status'=> 200, 'message'=> 'Request send successfullly to valet manager', 'data'=> $request_data]);
    }



    public function cancel_request($request_id = NULL)
    {
        if ($request_id == NULL)
            return response()->json(['status' => 202, 'message' => 'Please send request id']);

        $request = VehicleRequest::where(['id' => $request_id, 'customer_id' => Auth::user()->id])->first();
        if (!empty($request)) {
            if ($request->status == 1) {
                return response()->json(['status' => 203, 'message' => 'You can not cancel this request']);
            }
        } else {
            return response()->json(['status' => 203, 'message' => 'Invalid Request']);
        }

        VehicleRequest::where(['id' => $request_id, 'customer_id' => Auth::user()->id])->update(['status' => 2, 'valet' => NULL]);
        return response()->json(['status' => 200, 'message' => 'Request Canceled Successfully']);
    }

    public function all_cancel_request()
    {
        $canceled_requests = VehicleRequest::where(['customer_id' => Auth::user()->id])->where(['status' => 2])->get();
        return response()->json(['status' => 200, 'message' => 'All Cancelled Requests', 'data' => $canceled_requests]);
    }

    public function undo_request($request_id = NULL)
    {
        if ($request_id == NULL)
            return response()->json(['status' => 202, 'message' => 'Please send request id']);

        VehicleRequest::where(['id' => $request_id, 'customer_id' => Auth::user()->id])->update(['status' => 0, 'valet' => NULL]);
        return response()->json(['status' => 200, 'message' => 'Request Undo Successfully']);
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
            return response(['message' => $error], 400);
        }
        $vehicle_request = VehicleRequest::where(['id' => $request->request_id])->first();
        if (empty($vehicle_request)) {
            return response(['message' => 'Invalid Vehicle Request Id'], 400);
        }

        $valet_manger = User::where(['id' => $vehicle_request->valet_manager])->first();
        if ($valet_manger->tip_type == 0) // pooled tip
        {
            User::where(['id' => $valet_manger->id])->update(['total_tip' => $valet_manger->total_tip + $request->tip]);
        } else {
            User::where(['id' => $vehicle_request->valet])->update(['total_tip' => $valet_manger->total_tip + $request->tip]);
        }

        $feedback = [
            'customer_id' => Auth::user()->id,
            'request_id' => $request->request_id,
            'valet_id' => 2,
            // 'valet_id' => $vehicle_request->valet,
            'rating' => $request->rating,
            'message' => $request->message,
            'tip_type' => $valet_manger->tip_type,
            'tip' => $request->tip,
            'processed' => 1
        ];


        Feedback::create($feedback);
        $completed = VehicleRequest::where(['customer_id' => Auth::user()->id, 'status' => 1])->update(['processed' => 1]);


        return response(['status' => 200, 'message' => 'feedback submitted successfully'], 200);
    }
    public function getRequests(Request $request)
    {
        $completed = VehicleRequest::where(['customer_id' => Auth::user()->id, 'status' => 1, 'payment_done_by_customer' => 0])->first();
        // $completed = VehicleRequest::where(['customer_id' => Auth::user()->id, 'status' => 1])->first();
        $active = VehicleRequest::where(['customer_id' => Auth::user()->id, 'status' => 0])->first();
        return response(['active' => $active, 'completed' => $completed], 200);
    }
    public function getRequestsold()
    {
        $currentDateTime = Carbon::now();

        $records = VehicleRequest::where('customer_id', Auth::user()->id)->get();

        $requests = VehicleRequest::where(['customer_id' => Auth::user()->id, 'status' => 1])->with('feedback')->get();
        //    $completed = VehicleRequest::where(['customer_id'=> Auth::user()->id, 'status'=> 1])->get();
        //    $incompleted = VehicleRequest::where(['customer_id'=> Auth::user()->id, 'status'=> 0])->get();


        $expiredRecords = $records->filter(function ($record) use ($currentDateTime) {
            return Carbon::parse($record->duration)->isPast();
        });

        $activeRecords = $records->filter(function ($record) use ($currentDateTime) {
            return Carbon::parse($record->duration)->isFuture();
        });
        return response(['expire' => $expiredRecords, 'activeRecords' => $activeRecords], 200);

        // return $expiredRecords;


        return response(['message' => 'All Completed Requests', 'data' => $requests], 200);
    }
    public function get_latest_request()
    {
        $requests = VehicleRequest::where(['customer_id' => Auth::user()->id])->orderBy('id', 'DESC')->first();
        return response()->json(['status' => 200, 'message' => 'Latest Request', 'data' => $requests]);
    }

    public function completePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'rating' => 'required',
            'message' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }

        $vehicle_request = VehicleRequest::find($request->request_id);
        $valet = User::find($vehicle_request->valet);
        // **********************************  get Feedback for Request *****************************************
        $feedback_request = Feedback::where(['valet_id' => $vehicle_request->valet, 'processed' => 0])->first();
        $feedback_request->message = $request->message;
        $feedback_request->tip = $request->amount;
        $feedback_request->rating = $request->rating;
        $feedback_request->save();

        // ******************************************************************************************************

        // **********************************  save for Request details *****************************************
        $vehicle_request->payment_done_by_customer = 1;
        $vehicle_request->save();
        // ******************************************************************************************************

        // ********************************** add amount to user ***********************************************
        $valet->is_free = 1;
        $valet->total_tip =  $valet->total_tip  + $request->amount;
        $valet->save();

        // ******************************************************************************************************
        return response(['message' => 'done ...'], 200);
    }
}
