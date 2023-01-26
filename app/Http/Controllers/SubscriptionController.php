<?php

namespace App\Http\Controllers;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Traits\BraintreeGatewayTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    use BraintreeGatewayTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        DB::beginTransaction();
        $plan = Plan::where('braintree_plan', $request->get('plan'))->first();
        // dd($plan);
        if (!empty($plan)) {
            $user = Auth::user();
            $customer = $this->createCustomer($user, $request);
            if ($customer->success) {
                $this->userUpdate($customer->customer, $user);
            }
            $subscribeResult = $this->gateway()->subscription()->create([
                'paymentMethodToken' => $customer->customer->paymentMethods[0]->token,
                'planId' => $plan->braintree_plan
            ]);
            if ($subscribeResult->success == true) {
                if ($this->storeSubscription($subscribeResult, $plan, $user)) {
                    DB::commit();
                    return redirect()->route('home')->with('success', 'Your plan subscribed successfully');
                }
            }
            DB::rollBack();
            return redirect()->route('home')->with('error', 'Something went wrong.');
        }
        DB::rollBack();
        return redirect()->route('home')->with('error', 'Plan not found.');
    }

    /* store subscription data in the database */
    public function storeSubscription($subscribeResult, $plan, $user)
    {
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->name = $plan->name;
        $subscription->plan_id = $plan->id;
        $subscription->price = $plan->price;
        $subscription->braintree_id = $subscribeResult->subscription->id;
        $subscription->braintree_plan = $subscribeResult->subscription->transactions[0]->planId;
        $subscription->quantity = 1;
        $subscription->save();
        return true;
    }

    /* update user data */
    public function userUpdate($customer, $user)
    {
        $userData = [
            'customer_id' => $customer->id,
        ];
        if (!empty($customer->creditCards)) {
            $userData['card_brand'] = $customer->creditCards[0]->cardType;
            $userData['card_last_four'] = $customer->creditCards[0]->last4;
        }
        if (!empty($customer->paypalAccounts)) {
            $userData['paypal_email'] = $customer->paypalAccounts[0]->email;
        }
        User::where('id', $user->id)->update($userData);
    }

    /* cancel user subscription */
    public function cancel()
    {
        $subscription = Subscription::where('user_id', Auth::user()->id)->first(['braintree_id']);
        if (!empty($subscription)) {
            $result = $this->gateway()->subscription()->cancel($subscription->braintree_id);
            if ($result->success) {
                $user = Auth::user();
                Subscription::where('user_id', $user->id)->delete();
                User::where('id',$user->id)->update(['paypal_email' => null,'card_brand'=>null,'card_last_four'=>null]);
                return response()->json([
                    'success' => true,
                    'status' => 200
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'status' => 500
        ]);
    }

    /* create customer from user data */
    public function createCustomer($user, $request)
    {
        if (empty($user->customer_id)) {
            $customer = $this->gateway()->customer()->create([
                'firstName' => $user->name,
                'email' => $user->email,
                'paymentMethodNonce' => $request->payment_method_nonce
            ]);
        } else {
            $customer = $this->gateway()->customer()->update(
                $user->customer_id,
                [
                    'paymentMethodNonce' => $request->payment_method_nonce
                ]
            );
        }
        return $customer;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
