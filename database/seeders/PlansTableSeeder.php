<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Traits\BraintreeGatewayTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlansTableSeeder extends Seeder
{
    use BraintreeGatewayTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
            'name' => 'monthly plan',
            'description' => 'User monthly plan',
            'price' => 10,
            'billing_frequency' => 1,
            'currencyIsoCode' => 'CAD'
            ],
            [
            'name' => 'yearly plan',
            'description' => 'User yearly plan',
            'price' => 50,
            'billing_frequency' => 12,
            'currencyIsoCode' => 'CAD'
            ],
        ];

        foreach($plans as $planVal){
            $result = $this->gateway()->plan()->create(
                [
                    'name' => $planVal['name'],
                    'description' => $planVal['description'],
                    'price' => $planVal['price'],
                    'billingFrequency' => $planVal['billing_frequency'],
                    'currencyIsoCode' => 'CAD'
                ]
            );
            if ($result->success) {
                $plan = new Plan();
                $plan->name = $planVal['name'];
                $plan->description = $planVal['description'];
                $plan->price = $planVal['price'];
                $plan->slug = Str::slug($planVal['name']);
                $plan->braintree_plan = $result->plan->id;
                $plan->billing_frequency = $planVal['billing_frequency'];
                $plan->save();
            }
        }
    }
}
