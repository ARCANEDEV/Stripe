<?php namespace Arcanedev\Stripe\Contracts;

/**
 * Interface  StripeObject
 *
 * @package   Arcanedev\Stripe\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface StripeObject
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get keys.
     *
     * @return array
     */
    public function keys();

    /* -----------------------------------------------------------------
     |  Main Functions
     | -----------------------------------------------------------------
     */

    /**
     * This unfortunately needs to be public to be used in Util.php
     * Return The object constructed from the given values.
     *
     * @param  array   $values
     * @param  string  $options
     *
     * @return \Arcanedev\Stripe\StripeObject
     */
    public static function scopedConstructFrom($values, $options);

    /**
     * Refreshes this object using the provided values.
     *
     * @param  array    $values
     * @param  string   $apiKey
     * @param  bool     $partial
     */
    public function refreshFrom($values, $apiKey, $partial = false);
}
