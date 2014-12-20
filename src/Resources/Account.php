<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\SingletonResource;

/**
 * @property null id
 * @property mixed|null email
 * @property mixed|null charges_enabled
 * @property mixed|null details_submitted
 */
class Account extends SingletonResource
{
    /* ------------------------------------------------------------------------------------------------
     |  Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string|null $apiKey
     *
     * @return Account
     */
    public static function retrieve($apiKey = null)
    {
        $class = get_class();

        return self::scopedSingletonRetrieve($class, $apiKey);
    }
}
