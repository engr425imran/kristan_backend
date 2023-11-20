<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Transaction;
use Auth;
use Stripe;
use Exception;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stripe_token' => 'required',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => 202, 'message'=> $error]);
        }
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
          try{
            Stripe\Charge::create ([
                "amount" => $request->amount * 100,
                "currency" => "usd",
                "source" => $request->stripe_token,
        ]);
        }catch (Exception $e) {
            return response()->json(['status'=> 202, 'message'=> 'Payment Failed due to:'.$e]);
        };
        return response()->json(['status'=> 200, 'message'=> 'Payment Successfull']);
    }
    
    public function withdraw(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::where(['id'=> $user_id])->first();
        if($request->amount <= $user->total_tip){
            $transaction = [
                'valet' => $user_id,
                'valet_manager'=> $user->created_by,
                'status'=>0,
                'amount'=>$request->amount,
                ];
                Transaction::create($transaction);
            // User::where(['id'=> $user_id])->update(['total_tip'=> $user->total_tip - $request->amount]);
            return response()->json(['status'=>200, 'message'=> 'Amount WithDraw Request send to your valet manager']);
        }else{
            return response()->json(['status'=>202, 'meesage'=> 'Amount You entered is more than your balance']);
        }
    }
}
