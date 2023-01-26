<?php

namespace App\Http\Controllers;

use App\Traits\BraintreeGatewayTrait;
use Illuminate\Http\Request;

class BraintreeController extends Controller
{
    use BraintreeGatewayTrait;

    public function index()
    {
        return response()->json([
            'data' => [
                'token' => $this->gateway()->clientToken()->generate()
            ]
        ]);
    }
}
