<?php namespace Arcanedev\Stripe;

/**
 * Class     SingletonResource
 *
 * @package  Arcanedev\Stripe
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SingletonResource extends StripeResource
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a singleton resource
     *
     * @param  string             $class
     * @param  array|string|null  $apiKey
     *
     * @return SingletonResource
     */
    protected static function scopedSingletonRetrieve($class, $apiKey = null)
    {
        /** @var self $instance */
        $instance = new $class(null, $apiKey);
        $instance->refresh();

        return $instance;
    }

    /**
     * Get resource URL.
     *
     * @param  string  $class
     *
     * @return string - The endpoint associated with this singleton class.
     */
    public static function classUrl($class = '')
    {
        return '/v1/'.self::className($class);
    }

    /**
     * The endpoint associated with this singleton API resource.
     *
     * @param  string  $class
     *
     * @return string
     */
    public function instanceUrl($class = '')
    {
        return (string) self::classUrl(get_class($this));
    }
}
