<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\BalanceInterface;
use Arcanedev\Stripe\SingletonResource;

/**
 * Balance Object
 *
 * @link https://stripe.com/docs/api/php#balance_object
 *
 * @property string object   // "balance"
 * @property bool   livemode
 * @property array  available
 * @property array  pending
 */
class Balance extends SingletonResource implements BalanceInterface
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
    public static function retrieve($options = null)
    {
        return parent::scopedSingletonRetrieve(get_class(), $options);
    }
}
