<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  Balance
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Balance
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
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
