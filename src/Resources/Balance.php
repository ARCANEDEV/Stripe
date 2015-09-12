<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\BalanceInterface;
use Arcanedev\Stripe\SingletonResource;

/**
 * Class     Balance
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#balance_object
 *
 * @property  string  object    // "balance"
 * @property  bool    livemode
 * @property  array   available
 * @property  array   pending
 *
 * @todo:     Complete the properties.
 */
class Balance extends SingletonResource implements BalanceInterface
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
    public static function retrieve($options = null)
    {
        return self::scopedSingletonRetrieve(get_class(), $options);
    }
}
