<?php
namespace App\Classes;
use CoinbaseCommerce\ApiClient;

class Coinbase {
    private $coinbase;
    public function __construct() {
        ApiClient::init(getenv('COINBASE_API'));
    }

    public function charge($data) {
        $siteUrl = getenv('APP_URL');
        $chargeData = [
            'name' => 'Charge for ' . $data['email'],
            'description' => 'Charge for ' . $data['email'],
            'local_price' => [
                'amount' => $data['amount'],
                'currency' => 'USD'
            ],
            'pricing_type' => 'fixed_price',
            'metadata' => [
                'customer_email' => $data['email'],
            ],
        ];
        $chargeObj = \CoinbaseCommerce\Resources\Charge::create($chargeData);
        return $chargeObj;
    }
}
