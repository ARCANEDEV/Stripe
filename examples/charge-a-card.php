<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Resources\Charge;

Stripe::setApiKey('your-stripe-api-key');

$myCard = [
    'number'    => '4242424242424242',
    'exp_month' => 5,
    'exp_year'  => 2015
];

$charge = Charge::create([
    'card'      => $myCard,
    'amount'    => 2000,
    'currency'  => 'usd'
]);

var_dump($charge);
