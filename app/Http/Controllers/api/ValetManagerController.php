<?php

namespace App\Http\Controllers\api;

use App\Feedback;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerificationCode;
use App\VehicleRequest;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use App\DistributedTip;


use Illuminate\Http\Request;

class ValetManagerController extends Controller
{
    public function add_valet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }

        $check_email = User::where(['email' => $request->email])->first();

        if(!empty($check_email))
        {
            return response()->json(['status' => 203, 'message'=> 'Email Already Exist']);
        }

        $six_digit_random_number = mt_rand(100000, 999999);
        $create_valet = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($six_digit_random_number),
            'first_login' => 0,
            'is_verified' => 1,

            'user_type' => 2,
            // 'otp' => Hash::make($six_digit_random_number),
            'created_by'=> Auth::user()->id,
        ];

        User::create($create_valet);
        Mail::to($request->email)->send(new VerificationCode($six_digit_random_number));
        return response()->json(['status'=>200, 'message'=> 'Valet Created Successfully']);
    }

    public function get_all_valet()
    {
         $vehicle_requests = VehicleRequest::where(['valet_manager'=> Auth::user()->id])->pluck('id');
        $feedback = Feedback::whereIn('request_id', $vehicle_requests)->where(['tip_type'=>0]);
        $total_pooled_tip = $feedback->sum('tip');

        $valets = User::where(['created_by'=> Auth::user()->id])->get();
        return response()->json(['status'=>200, 'message'=> 'All Valet', 'data'=>$valets, 'total_pooled_tip'=> $total_pooled_tip]);

    }

    public function locations()
    {
        $locations = User::where(['user_type'=> 1])->Where('location', '!=', NULL)->select('location')->get();
        return response()->json(['status'=>200, 'message'=> 'All Locations', 'data'=> $locations]);
    }

    public function all_vehicle_request()
    {
        $pending_requests = VehicleRequest::where(['status'=> 0])->where(['valet_manager'=> Auth::user()->id])->count();
        $completed_requests = VehicleRequest::where(['status'=> 1])->where(['valet_manager'=> Auth::user()->id])->count();
        $requests = VehicleRequest::where(['valet_manager'=> Auth::user()->id])->where('status', '!=', 2)->with(['customer'=> function($query){
            $query->where(['user_type'=>0]);
        }])->with('valetdetails')->orderBy('id','DESC')->get();

        return response()->json(['status'=>200, 'message'=> 'All Vehicle Request', 'data'=> $requests, 'pending_requests'=> $pending_requests, 'completed_requests'=> $completed_requests]);
    }

    public function assign_to_valet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'valet_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }

        $check_request = VehicleRequest::where(['id'=> $request->request_id])->first();

        if(empty($check_request))
        {
            return response()->json(['status'=> 203, 'message'=> 'Invalid Request Id']);
        } else {
            if($check_request->status== 1)
            {
                return response()->json(['status'=>203, 'message'=> 'This Request is already Completed']);
            }

            if($check_request->status== 2)
            {
                return response()->json(['status'=>203, 'message'=> 'This Request is Canceled By Client']);
            }
            
            if($check_request->valet != NULL)
            {
                return response()->json(['status'=>203, 'message'=> 'This Request is already Assign to another valet']);
            }
        }
        
        $user = User::where(['id'=>$request->valet_id, 'user_type'=> 2])->first();
        if(empty($user))
        {
            return response()->json(['status'=>203, 'message'=> 'Invalid Valet Id']);
        } else {
            if($user->is_free == 0)
            {
                return response()->json(['status'=>203, 'message'=> 'This Valet is already busy']);
            }
        }

        VehicleRequest::where(['id'=> $request->request_id])->update(['valet'=> $request->valet_id]);
        User::where(['id'=> $request->valet_id])->update(['is_free'=> 0]);
        return response()->json(['status'=> 200, 'message'=> 'Valet Assign Successfully']);
    }

    public function tip_type(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required', // tip type 0: pool tip , 1: direct tip
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }
        
        if($request->type == 0){
             $validator = Validator::make($request->all(), [
            'duration' => 'required', // duration 0: daily , 1: weekly, 2: monthly
            ]);
             if ($validator->fails()) {
                $error = $validator->errors()->first();
                return response()->json(['status' => 202, 'message'=> $error]);
            }
        }

        if(Auth::user()->user_type == 1)
        {
            $data['tip_type'] = $request->type;
            if($request->type == 0)
            $data['duration'] = $request->duration;
            
            User::Where(['id'=> Auth::user()->id])->update($data);
            return response()->json(['status' => 200, 'message'=> 'tip type updated successfully']);
        } else {
            return response()->json(['status' => 203, 'messgae' => 'Only Valet Manager Can set this']);
        }
    }

    public function unassign_valet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'valet_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }

        $check_request = VehicleRequest::where(['id'=> $request->request_id])->first();

        if(empty($check_request))
        {
            return response()->json(['status'=> 203, 'message'=> 'Invalid Request Id']);
        } else {
            if($check_request->status== 1)
            {
                return response()->json(['status'=>203, 'message'=> 'This Request is already Completed']);
            }

            if($check_request->status== 2)
            {
                return response()->json(['status'=>203, 'message'=> 'This Request is Canceled By Client']);
            }
        }

        VehicleRequest::where(['id'=> $request->request_id])->update(['valet'=> NULL]);

        return response()->json(['status'=> 200, 'message'=> 'Valet Unassigned Successfully']);
    }

    public function get_feedback()
    {
        $feedback = NULL;
       $latest_request = VehicleRequest::where(['valet_manager'=> Auth::user()->id])->orderBy('id','DESC')->first();
       if(!empty($latest_request))
       {
           $feedback = Feedback::where(['request_id'=> $latest_request->id])->select('id', 'request_id', 'customer_id', 'rating', 'message', 'tip')->first();
           if(!empty($feedback))
           {
                $feedback->ticket_number = $latest_request->ticket_number;
           }
       }

       return response()->json(['status'=> 200, 'message'=> 'Latest Feedback', 'data'=> $feedback]);
    }

    public function send_greetings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'greetings' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }

        Feedback::where(['id'=> $request->request_id])->update(['greetings' => $request->greetings]);
        return response()->json(['status'=> 200, 'message'=> 'Greetings Send successfully']);
    }
    
    public function feedback_reporting(Request $request)
    {
        $average_rating = 0;
        $vehicle_requests = VehicleRequest::where(['valet_manager'=> Auth::user()->id])->pluck('id');
        $feedback = Feedback::whereIn('request_id', $vehicle_requests);
        $total_feedback = $feedback->count();
        if($total_feedback > 0)
        $average_rating = $feedback->avg('rating'); 
        if($request->date_from != "" && $request->date_to != ""){
            $feedback = $feedback->where('created_at','>' , $request->date_from)->where('created_at','<' , $request->date_to);
        }
        
        $all_feedbacks = $feedback->with('vehicle_request')->get();
        
        return response()->json(['status'=> 200, 'message'=> 'Feedbacks', 'data'=> $all_feedbacks, 'avg_rating'=> $average_rating, 'total_feedback'=> $total_feedback]);
        
        
    }
    
    public function tip_reporting(Request $request)
    {
        $vehicle_requests = VehicleRequest::where(['valet_manager'=> Auth::user()->id])->pluck('id');
        $feedback = Feedback::whereIn('request_id', $vehicle_requests)->where(['tip_type'=>0]);
        $total_pooled_tip = $feedback->sum('tip');

        if($request->date_from != "" && $request->date_to != ""){
            $feedback = $feedback->where('created_at','>' , $request->date_from)->where('created_at','<' , $request->date_to);
        }
        $all_feedbacks = $feedback->get();
        return response()->json(['status'=> 200, 'message'=> 'Feedbacks', 'data'=> $all_feedbacks, 'total_pooled_tip'=> $total_pooled_tip, 'total_tip'=> Auth::user()->total_tip]);

    }
    
    public function update_contribution(Request $request)
    {
        // return $request->contribution;
        foreach($request->contribution as $index=>$user)
        {
           $user_id = $user['user'];
           $contribution = [
               'contribution' => isset($user['contribution']) ? $user['contribution'] : 0,
               'contribution_percentage' => isset($user['contribution_value']) ? $user['contribution_value'] : 0,
               ];
               
             User::where(['id'=> $user_id])->update($contribution); 
        }
        
        return response()->json(['status'=> 200, 'message'=> 'Contribution Updated Successfully']);
    }
    
    public function distriubte()
    {
        $user = Auth::user();
        if($user->user_type != 1){
            return response()->json(['status'=> 202, 'message'=> 'Only Valet Manger can do that']);
        }
        
        $total_tip = $user->total_tip;
        if($total_tip < 1){
            return response()->json(['status'=> 202, 'messgae'=> 'You don"t have enough balance']);
        }

        $valets = User::where(['created_by'=> $user->id, 'contribution'=> 1])->get();
        $distributed_tip = 0;
        foreach($valets as $valet){
            $percentage = $valet->contribution_percentage;
            $tip = ($percentage / 100) * $total_tip;
            User::where(['id'=> $valet->id])->update(['total_tip'=> $valet->total_tip + $tip]);
            $distributed_tip_data = [
                'tip' => $tip,
                'valet' => $valet->id,
                'valet_manager' => $user->id,
            ];
            DistributedTip::create($distributed_tip_data);
            $distributed_tip += $tip;
        }

        User::where(['id'=> $user->id])->update(['total_tip' => $total_tip - $distributed_tip]);
        return response()->json(['status'=>200, 'message'=> 'tip distrubetd successfully']);
        
    }
    
    public function payment_request()
    {
       $transactions = Transaction::where(['valet_manager'=> auth()->user()->id, 'status'=>0])->with('valet')->get();
       return response()->json(['status'=>200, 'message'=> 'All Transactions Requests', 'data'=> $transactions]);
    }
    
    public function update_payment_request(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'request_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }
        
        $transaction = Transaction::where(['id'=>$request->request_id, 'status'=>0])->first();
        if(empty($transaction)){
            return response()->json(['status'=>202, 'message'=> 'Invalid Request Id']);
        }
        $user = User::where(['id'=> $transaction->valet])->first();
        if(empty($user)){
             return response()->json(['status'=>202, 'message'=> 'Valet Not Available']);
        }
         Transaction::where(['id'=>$request->request_id])->update(['status'=>1]);
        if($transaction->amount > $user->total_tip){
            return response()->json(['status'=>202, 'message'=> 'Amount is more than available amount']);
        }
        User::where(['id'=> $user->id])->update(['total_tip'=> $user->total_tip - $transaction->amount]);
        return response()->json(['status'=>200, 'message'=> 'Request Approved Successfully']);
    }
}
