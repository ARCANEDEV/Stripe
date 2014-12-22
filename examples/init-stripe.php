<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$apiKey = getenv('STRIPE_API_KEY');

if (empty($apiKey)) {
    $apiKey = 'your-stripe-api-key';
}

// Init Stripe
use Arcanedev\Stripe\Stripe;

Stripe::init($apiKey);
