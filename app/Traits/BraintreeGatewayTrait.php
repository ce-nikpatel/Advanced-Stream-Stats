<?php
namespace App\Traits;
use Braintree;

trait BraintreeGatewayTrait
{
    public function gateway()
    {
        $gateway = new Braintree\Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchant_id'),
            'publicKey' => config('services.braintree.public_key'),
            'privateKey' => config('services.braintree.private_key')
        ]);
        return $gateway;
    }
}
