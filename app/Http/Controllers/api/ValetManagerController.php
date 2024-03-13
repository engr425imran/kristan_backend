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
use App\Jobs\SendVerificationOtp;



use Illuminate\Http\Request;

class ValetManagerController extends Controller
{

    // done ...
    public function addNewValet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(["message" => $error], 400);
        }
        $check_email = User::where(['email' => $request->email])->first();
        if (!empty($check_email)) {
            return response(["message" => 'Email Already Exist'], 409);
        }
        $six_digit_random_number = mt_rand(100000, 999999);
        $create_valet = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($six_digit_random_number),
            'first_login' => 0,
            'user_type' => 2,
            // 'otp' => Hash::make($six_digit_random_number),
            'created_by' => Auth::user()->id,
        ];

        User::create($create_valet);
        $this->sendOtp($request->email);
        return response(["message" => 'Valet Created Successfully'], 200);
    }



    // index screen 

    public function getAllVehicleRequest()
    {
        $manager_id = Auth::user()->id;
        $pending_requests = VehicleRequest::where(['status' => 0, 'valet' => null])->where(['valet_manager' => $manager_id])->get();
        $completed_requests = VehicleRequest::where(['status' => 1])->where(['valet_manager' => $manager_id])->get();
        $requests = VehicleRequest::where(['valet_manager' => $manager_id])->where('status', '!=', 2)->with(['customer' => function ($query) {
            $query->where(['user_type' => 0]);
        }])->with('valetdetails')->orderBy('id', 'DESC')->get();
        $valets = User::where(['created_by' => $manager_id, 'is_free' => 1, 'user_type' => 2])->get();
        $busy_valets = User::where(['created_by' => $manager_id, 'is_free' => 0, 'user_type' => 2])->get();
        $request_ids = VehicleRequest::where(['valet_manager' => $manager_id, 'status' => 1, 'payment_done_by_customer' => 1])->whereDate('updated_at', today())->pluck('id');
        $today_pooled_tip = Feedback::whereIn('request_id', $request_ids)->where('tip_type', 0)->sum('tip');

        return response(['pending_request' => $pending_requests, 'busy_valets' => $busy_valets, 'valets' => $valets, 'settled_requests' => $completed_requests, 'pooled_tip' => $today_pooled_tip], 200);
        // return response(['pending_request' => $pending_requests, 'settled_requests' => $completed_requests, 'valets' => $valets], 200);
        // return response(['message' => 'All Vehicle Request', 'data' => $requests, 'pending_request' => $pending_requests, 'settled_requests' => $completed_requests], 200);
    }

    // ----
    public function getAllVehcileRequestk()
    {
        $valets = User::where(['created_by' => Auth::user()->id, 'is_free' => 1, 'user_type' => 2])->get();
        $vehicle_requests = VehicleRequest::where(['valet_manager' => Auth::user()->id])->pluck('id');
        $feedback = Feedback::whereIn('request_id', $vehicle_requests)->where(['tip_type' => 0]);

        return response(["data" => $valets], 200);
    }

    public function getAllValet()
    {
        // $vehicle_requests = VehicleRequest::where(['valet_manager' => Auth::user()->id])->pluck('id');
        // $feedback = Feedback::whereIn('request_id', $vehicle_requests)->where(['tip_type' => 0]);
        // $total_pooled_tip = $feedback->sum('tip');

        $valets = User::where(['created_by' => Auth::user()->id, 'is_free' => 1, 'user_type' => 2])->get();
        return response(["data" => $valets], 200);
        // return response(['message' => 'All Valet', 'data' => $valets, 'total_pooled_tip' => $total_pooled_tip], 200);
    }



    public function sendOtp($email)
    {
        try {
            $resetPassword = false;
            $code = mt_rand(1000, 9999);
            User::where(['email' => $email])->update(['otp' => Hash::make($code)]);
            SendVerificationOtp::dispatch($email, $code, $resetPassword);
        } catch (\Throwable $th) {
            // throw $th;
        }
    }


    public function getALllocations()
    {
        $locations = User::where(['user_type' => 1])->Where('location', '!=', NULL)->select('location')->get();
        if ($locations) {
            return response(['data' => $locations], 200);
        }
        return response(['message' => 'No location found'], 404);
    }


    // done //
    public function assignToValet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'valet_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(["message" => $error], 400);
        }

        $check_request = VehicleRequest::where(['id' => $request->request_id])->first();

        if (empty($check_request)) {
            return response(["message" => 'Invalid Request Id'], 404);
        } else {
            if ($check_request->status == 1) {
                return response(["message" => 'This Request is already Completed'], 400);
            }

            if ($check_request->status == 2) {
                return response(["message" => 'This Request is Canceled By Client'], 400);
            }

            if ($check_request->valet != NULL) {
                return response(["message" => 'This Request is already Assign to another valet'], 400);
            }
        }

        $user = User::where(['id' => $request->valet_id, 'user_type' => 2])->first();
        if (empty($user)) {
            return response(["message" => 'Invalid Valet Id'], 400);
        } else {
            if ($user->is_free == 0) {
                return response(["message" => 'This Valet is already busy'], 400);
            }
        }

        VehicleRequest::where(['id' => $request->request_id])->update(['valet' => $request->valet_id]);
        User::where(['id' => $request->valet_id])->update(['is_free' => 0]);
        $pending_requests = VehicleRequest::where(['status' => 0, 'valet' => null])->where(['valet_manager' => auth()->user()->id])->get();

        return response(["message" => 'Valet Assign Successfully', 'pending_request' => $pending_requests], 201);
    }



    public function updateTipType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required', // tip type 0: pool tip , 1: direct tip
        ]);


        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }

        if ($request->type == 'pooled') {
            $validator = Validator::make($request->all(), [
                'tip_frequency' => 'required', // duration 0: daily , 1: weekly, 2: monthly
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return response(['message' => $error], 400);
            }
        }

        if (Auth::user()->user_type == 1) {
            $user = User::find(auth()->user()->id);

            if ($request->type == 1) {
                $user->tip_type = 1;
            }
            if ($request->type == 0) {
                $user->tip_type = 0;
                $user->pooled_tip_frequency = $request->tip_frequency;
            }
            $user->save();
            return response(['message' => 'tip type updated successfully', 'user' => $user], 203);
        } else {
            return response(['messgae' => 'Unauthorized access...'], 403);
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
            return response(['message' => $error], 400);
        }

        $check_request = VehicleRequest::where(['id' => $request->request_id])->first();

        if (empty($check_request)) {
            return response(['message' => 'Invalid Request Id'], 400);
        } else {
            if ($check_request->status == 1) {
                return response(['message' => 'This Request is already Completed'], 403);
            }

            if ($check_request->status == 2) {
                return response(['message' => 'This Request is Canceled By Client'], 400);
            }
        }

        VehicleRequest::where(['id' => $request->request_id])->update(['valet' => NULL]);

        return response(['message' => 'Valet Unassigned Successfully'], 200);
    }

    public function get_feedback()
    {
        $feedback = NULL;
        $latest_request = VehicleRequest::where(['valet_manager' => Auth::user()->id])->orderBy('id', 'DESC')->first();
        if (!empty($latest_request)) {
            $feedback = Feedback::where(['request_id' => $latest_request->id])->select('id', 'request_id', 'customer_id', 'rating', 'message', 'tip')->first();
            if (!empty($feedback)) {
                $feedback->ticket_number = $latest_request->ticket_number;
            }
        }

        return response(['message' => 'Latest Feedback', 'data' => $feedback], 200);
    }

    public function send_greetings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'greetings' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }

        Feedback::where(['id' => $request->request_id])->update(['greetings' => $request->greetings]);
        return response(['message' => 'Greetings Send successfully'], 203);
    }

    public function valetRatingReport(Request $request)
    {
        $average_rating = 0;
        $vehicle_requests = VehicleRequest::where(['valet_manager' => Auth::user()->id])->pluck('id');
        $feedback = Feedback::whereIn('request_id', $vehicle_requests);
        $total_feedback = $feedback->count();
        if ($total_feedback > 0)
            $average_rating = $feedback->avg('rating');
        if ($request->date_from != "" && $request->date_to != "") {
            $feedback = $feedback->where('created_at', '>', $request->date_from)->where('created_at', '<', $request->date_to);
        }

        $all_feedbacks = $feedback->with('vehicle_request')->get();

        return response(['message' => 'Feedbacks', 'data' => $all_feedbacks, 'avg_rating' => $average_rating, 'total_feedback' => $total_feedback], 200);
    }

    public function valetTipReport(Request $request)
    {
        $vehicle_requests = VehicleRequest::where(['valet_manager' => Auth::user()->id])->pluck('id');
        $feedback = Feedback::whereIn('request_id', $vehicle_requests)->where(['tip_type' => 0]);
        $total_pooled_tip = $feedback->sum('tip');

        if ($request->date_from != "" && $request->date_to != "") {
            $feedback = $feedback->where('created_at', '>', $request->date_from)->where('created_at', '<', $request->date_to);
        }
        $all_feedbacks = $feedback->get();
        return response(['message' => 'Feedbacks', 'data' => $all_feedbacks, 'total_pooled_tip' => $total_pooled_tip, 'total_tip' => Auth::user()->total_tip], 200);
    }

    public function update_contribution(Request $request)
    {
        // return $request->contribution;
        foreach ($request->contribution as $index => $user) {
            $user_id = $user['user'];
            $contribution = [
                'contribution' => isset($user['contribution']) ? $user['contribution'] : 0,
                'contribution_percentage' => isset($user['contribution_value']) ? $user['contribution_value'] : 0,
            ];

            User::where(['id' => $user_id])->update($contribution);
        }

        return response(['message' => 'Contribution Updated Successfully'], 203);
    }

    public function distriubte()
    {
        $user = Auth::user();
        if ($user->user_type != 1) {
            return response(['message' => 'Only Valet Manger can do that'], 403);
        }

        $total_tip = $user->total_tip;
        if ($total_tip < 1) {
            return response(['messgae' => 'You don"t have enough balance'], 400);
        }

        $valets = User::where(['created_by' => $user->id, 'contribution' => 1])->get();
        $distributed_tip = 0;
        foreach ($valets as $valet) {
            $percentage = $valet->contribution_percentage;
            $tip = ($percentage / 100) * $total_tip;
            User::where(['id' => $valet->id])->update(['total_tip' => $valet->total_tip + $tip]);
            $distributed_tip_data = [
                'tip' => $tip,
                'valet' => $valet->id,
                'valet_manager' => $user->id,
            ];
            DistributedTip::create($distributed_tip_data);
            $distributed_tip += $tip;
        }

        User::where(['id' => $user->id])->update(['total_tip' => $total_tip - $distributed_tip]);
        return response(['message' => 'tip distrubetd successfully'], 203);
    }

    public function payment_request()
    {
        $transactions = Transaction::where(['valet_manager' => auth()->user()->id, 'status' => 0])->with('valet')->get();
        return response(['message' => 'All Transactions Requests', 'data' => $transactions], 200);
    }

    public function update_payment_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }

        $transaction = Transaction::where(['id' => $request->request_id, 'status' => 0])->first();
        if (empty($transaction)) {
            return response(['message' => 'Invalid Request Id'], 400);
        }
        $user = User::where(['id' => $transaction->valet])->first();
        if (empty($user)) {
            return response(['message' => 'Valet Not Available'], 404);
        }
        Transaction::where(['id' => $request->request_id])->update(['status' => 1]);
        if ($transaction->amount > $user->total_tip) {
            return response(['message' => 'Amount is more than available amount'], 400);
        }
        User::where(['id' => $user->id])->update(['total_tip' => $user->total_tip - $transaction->amount]);
        return response(['message' => 'Request Approved Successfully'], 200);
    }
}
