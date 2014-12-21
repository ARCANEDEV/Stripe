<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\BalanceInterface;
use Arcanedev\Stripe\SingletonResource;

/**
 * @property mixed|null object
 * @property mixed|null available
 * @property mixed|null pending
 */
class Balance extends SingletonResource implements BalanceInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string|null $apiKey
     *
     * @return Balance
     */
    public static function retrieve($apiKey = null)
    {
        $class = get_class();

        return self::scopedSingletonRetrieve($class, $apiKey);
    }
}
