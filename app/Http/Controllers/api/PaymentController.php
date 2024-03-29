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
use Stripe\PaymentIntent;


class PaymentController extends Controller
{
    public function intent()
    {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $intent = PaymentIntent::create([
                'amount' => 1000, // Amount in cents
                'currency' => 'usd',
                // Add other payment intent parameters as needed
            ]);
            return $intent->client_secret;
        } catch (Exception $e) {
            return response(['message' => 'Payment Failed due to:' . $e], 400);
        };
        return "done";
    }
    public function paymentIntent(Request $request)
    {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $intent = PaymentIntent::create([
                'amount' => $request->amount, // Amount in cents
                'currency' => 'usd',
                // Add other payment intent parameters as needed
            ]);
            return $intent->client_secret;
        } catch (Exception $e) {
            return response(['message' => 'Payment Failed due to:' . $e], 400);
        };
        return "done";
    }
    public function token()
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $intent = PaymentIntent::create([
                'amount' => 1000, // Amount in cents
                'currency' => 'usd',
                // Add other payment intent parameters as needed
            ]);
            // retry()
            return $intent->client_secret;
        } catch (Exception $e) {
            return response(['message' => 'Payment Failed due to:' . $e], 400);
        };
        return "done";
    }
    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stripe_token' => 'required',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            Stripe\Charge::create([
                "amount" => $request->amount * 100,
                "currency" => "usd",
                "source" => $request->stripe_token,
            ]);
        } catch (Exception $e) {
            return response(['message' => 'Payment Failed due to:' . $e], 400);
        };
        return response(['message' => 'Payment Successfull'], 200);
    }

    public function withdrawValetRequest(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::where(['id' => $user_id])->first();
        if ($request->amount <= $user->total_tip) {
            $transaction = [
                'valet' => $user_id,
                'valet_manager' => $user->created_by,
                'status' => 0,
                'amount' => $request->amount,
            ];
            Transaction::create($transaction);
            // $user->total_tip
            return response(['message' => 'Amount WithDraw Request send to your valet manager', 'user' => $user], 200);
        } else {
            return response(['meesage' => 'Amount You entered is more than your balance'], 400);
        }
    }

    public function newImple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response(['message' => $error], 400);
        }
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $customer = $stripe->customers->create();
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $request->amount,
                'customer' => $customer->id,
                'currency' => 'usd',
            ]);

            $ephemeralKey = $stripe->ephemeralKeys->create([
                'customer' => $customer->id,
            ], [
                'stripe_version' => '2023-10-16',
            ]);
            return response([
                'amount' => $request->amount,
                'customer' => $customer->id,
                'paymentIntent' => $paymentIntent->client_secret,
                'ephemeralKey' => $ephemeralKey->secret
            ], 200);
            //code...
        } catch (\Throwable $th) {
            return response(['error' => $th], 405);
            //throw $th;
        }
    }
    public function newImplex(Request $request)
    {
        $stripe = new \Stripe\StripeClient('sk_test_R2t8wcS9wme8qsbYt6Uv90z8005yOVavtQ');
        $customer = $stripe->customers->create();
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => 1099,
            'customer' => $customer->id,
            'currency' => 'usd',
            // In the latest version of the API, specifying the `automatic_payment_methods` parameter
            // is optional because Stripe enables its functionality by default.
            // 'automatic_payment_methods' => [
            //     'enabled' => 'true',
            // ],
        ]);

        $ephemeralKey = $stripe->ephemeralKeys->create([
            'customer' => $customer->id,
        ], [
            'stripe_version' => '2023-10-16',
        ]);
        // return $ephemeralKey;
        return response([
            'amount' => $request->amount,
            'customer' => $customer->id,
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey' => $ephemeralKey->secret
        ], 200);
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => 1099,
            'customer' => $customer->id,
            // In the latest version of the API, specifying the `automatic_payment_methods` parameter
            // is optional because Stripe enables its functionality by default.
            // 'automatic_payment_methods' => [
            //     'enabled' => 'true',
            // ],
        ]);

        // dd($paymentIntent);
    }
}
