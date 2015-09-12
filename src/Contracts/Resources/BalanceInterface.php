<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  BalanceInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface BalanceInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a balance.
     *
     * @link   https://stripe.com/docs/api/php#retrieve_balance
     *
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($options = null);
}
