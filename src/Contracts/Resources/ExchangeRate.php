<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  ExchangeRate
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface ExchangeRate
{
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
    public static function retrieve($currency, $options = null);

    /**
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection
     */
    public static function all($params = null, $options = null);
}
