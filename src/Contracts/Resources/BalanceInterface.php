<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\Balance;

interface BalanceInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a balance
     * @link https://stripe.com/docs/api/php#retrieve_balance
     *
     * @param  string|null $apiKey
     *
     * @return Balance
     */
    public static function retrieve($apiKey = null);
}
