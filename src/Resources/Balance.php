<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\SingletonResource;

/**
 * @property mixed|null object
 * @property mixed|null available
 * @property mixed|null pending
 */
class Balance extends SingletonResource
{
    /**
     * @param string|null $apiKey
     *
     * @return Balance
     */
    public static function retrieve($apiKey=null)
    {
        $class = get_class();

        return self::scopedSingletonRetrieve($class, $apiKey);
    }
}
