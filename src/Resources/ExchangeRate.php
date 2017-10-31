<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\ExchangeRate as ExchangeRateContract;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     ExchangeRate
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ExchangeRate extends StripeResource implements ExchangeRateContract
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get The name of the class, with namespacing and underscores stripped.
     *
     * @param  string  $class
     *
     * @return string
     */
    public static function className($class = '')
    {
        return 'exchange_rate';
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * @param  array|string       $currency
     * @param  array|string|null  $options
     *
     * @return self
     */
    public static function retrieve($currency, $options = null)
    {
        return self::scopedRetrieve($currency, $options);
    }

    /**
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection
     */
    public static function all($params = null, $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
