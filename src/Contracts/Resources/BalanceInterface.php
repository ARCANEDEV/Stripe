<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Resources\Balance;

/**
 * Interface BalanceInterface
 * @package Arcanedev\Stripe\Contracts\Resources
 */
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
     * @param  array|string|null $options
     *
     * @return Balance
     */
    public static function retrieve($options = null);
}
