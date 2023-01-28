<?php

namespace App\Http\Controllers;

use App\Traits\BraintreeGatewayTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BraintreeController extends Controller
{
    use BraintreeGatewayTrait;

    public function index()
    {
        if (isset(Auth::user()->customer_id)) {
            return response()->json([
                'data' => [
                    'token' => $this->gateway()->clientToken()->generate([
                        "customerId" => Auth::user()->customer_id
                    ])
                ]
            ]);
        } else {
            return response()->json([
                'data' => [
                    'token' => $this->gateway()->clientToken()->generate()
                ]
            ]);
        }
    }
}
