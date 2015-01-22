<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\Account;

interface AccountInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve an account
     * @link https://stripe.com/docs/api/php#retrieve_account
     *
     * @param  string|null $apiKey
     *
     * @return Account
     */
    public static function retrieve($apiKey = null);
}
