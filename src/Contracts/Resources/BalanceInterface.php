<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Balance Object Interface
 *
 * @link https://stripe.com/docs/api/php#balance_object
 *
 * @property string object   // "balance"
 * @property bool   livemode
 * @property array  available
 * @property array  pending
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
     * @param string|null $apiKey
     *
     * @return BalanceInterface
     */
    public static function retrieve($apiKey = null);
}
